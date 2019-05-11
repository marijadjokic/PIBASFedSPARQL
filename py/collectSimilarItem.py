# -*- coding: utf-8 -*-
from __future__ import division, unicode_literals
import math

from SPARQLWrapper import SPARQLWrapper, JSON
import time
import re
import operator
import porter
import validators
import numpy as np
from numpy import zeros, dot
from numpy.linalg import norm
from collections import Counter
import sys
from stop_words import get_stop_words
import ssl
import warnings
with warnings.catch_warnings():
    warnings.filterwarnings("ignore", category=FutureWarning)
    import sklearn
from textblob import TextBlob as tb
ssl._create_default_https_context = ssl._create_unverified_context

stop_words = get_stop_words('english')
splitter = re.compile("[a-z0-9]+", re.I)
stemmer = porter.PorterStemmer()


fp = open(my_file, "r")

lines = [line.rstrip('\n') for line in fp]

my_template_id = lines[0]
my_subject_uris = lines[1].split(',')
my_endpoints = lines[2].split(',')
my_connections = dict(x.split('=') for x in (lines[3]).split(','))
my_for_similar = lines[4]


def tf(word, doc):
    return doc.words.count(word) / len(doc.words)


def n_containing(word, doc_list):
    return sum(1 for doc in doc_list if word in doc)


def idf(word, doc_list):
    return math.log(len(doc_list) / (1 + n_containing(word, doc_list)))


def tfidf(word, doc, doc_list):
    return tf(word, doc) * idf(word, doc_list)


stop_words = get_stop_words('english')
non_selected_predicates = ["http://rdf.ebi.ac.uk/terms/chembl#organismName",
                           "http://rdf.ebi.ac.uk/terms/chembl#targetType",
                           "http://bio2rdf.org/drugbank_vocabulary:organism"]


def create_documents(my_subject_uris, my_endpoints):
    list_of_document = []
    lists_of_predicates_and_object = []
    for i in range(0, len(my_subject_uris)):
        document = ""
        list_of_predicates_and_object = {}
        sparql = SPARQLWrapper(my_endpoints[i])
        sparql.setQuery(
            """
SELECT DISTINCT ?predicate ?object
WHERE
{

  <%s> ?predicate ?object

}
""" % (my_subject_uris[i]))
        sparql.setReturnFormat(JSON)
        time.sleep(2)
        final_results = sparql.query().convert()
        for result in final_results["results"]["bindings"]:
         if result["predicate"]["value"] not in non_selected_predicates:
            if validators.url(result["object"]["value"])!= True:
                if result["object"]["value"] not in document:
                    if result["object"]["value"] not in stop_words:
                        document += " " + str((result["object"]["value"]))
                        list_of_predicates_and_object[result["object"]["value"]] = result["predicate"]["value"]

        lists_of_predicates_and_object.append(list_of_predicates_and_object) 
        list_of_document.append(tb(document))
    return list_of_document, lists_of_predicates_and_object


def select_list_of_words_for_predicates():
    list_of_words_for_predicates = []
    bloblist, lists_of_predicates_and_object = create_documents(my_subject_uris, my_endpoints)
    for i, blob in enumerate(bloblist):
        scores = {word: tfidf(word, blob, bloblist) for word in blob.words if word not in stop_words}
        sorted_words = sorted(scores.items(), key=lambda x: x[1], reverse=True)
        for word, score in sorted_words[:5]: 
            if word not in list_of_words_for_predicates:
                list_of_words_for_predicates.append(word)
    return list(set(list_of_words_for_predicates)), lists_of_predicates_and_object


# ***************************Functions for finding most similar properties****************

WORD = re.compile(r'\w+')


def get_cosine(vec1, vec2):
    """
    Function for calculation of cosine similarity measure between vectors.
    @parm:
    vec1: vector
    vec2: vector
    """
    numerator = dot(vec1, vec2)
    denominator = (norm(vec1) * norm(vec2))
    if not denominator or denominator == 0.0:
        return 0.0
    else:
        return float(numerator) / float(denominator)


def text_to_vector(text):
    words = WORD.findall(text)
    return Counter(words)


def add_word(word, d):
    """
       Adds a word the a dictionary for words/count
       first checks for stop words
           the converts word to stemmed version
    """
    w_lower = word.lower()
    w = splitter.findall(w_lower)
    if w[0] not in stop_words:
        ws = stemmer.stem(w[0], 0, len(w[0]) - 1)
        d.setdefault(ws, 0)
        d[ws] += 1


def doc_vec(doc, key_idx):
    """
    Convert document to vector.
    @doc: string (document)
    @key_idx: dictionary

    """
    v = zeros(len(key_idx))
    for word in splitter.findall(doc):
        keydata = key_idx.get(stemmer.stem(word, 0, len(word) - 1).lower(), None)
        if keydata: v[keydata[0]] = 1
    return v


def compare(doc1, doc2):
    # strip all punctuation but - and '
    # convert to lower case
    # store word/occurance in dict
    all_words = dict()

    for dat in [doc1, doc2]:
        [add_word(w, all_words) for w in splitter.findall(dat)]
    key_idx = dict() 
    keys = all_words.keys()
    keys.sort()
    for i in range(len(keys)):
        key_idx[keys[i]] = (i, all_words[keys[i]])
    del keys
    del all_words
    v1 = doc_vec(doc1, key_idx)
    v2 = doc_vec(doc2, key_idx)
    if norm(v1) * norm(v2) != 0:
        result_of_compare = (dot(v1, v2) / (norm(v1) * norm(v2)))
        if math.isnan(result_of_compare):
            return 0.0
        else:
            return result_of_compare


def similarItems(template_id, subject_uris, endpoints, connections, for_similar):
    pro = {}
    chembl_endpoints = 0
    list_of_words_for_predicate_selection, lists_of_predicates_and_object = select_list_of_words_for_predicates()
    list_of_predicates = []
    for i in range(0, len(subject_uris)):
        pro[connections[endpoints[i]]] = 'http://www.w3.org/2000/01/rdf-schema#label,http://purl.org/dc/terms/title,'
        sparql = SPARQLWrapper(endpoints[i])
        sparql.setQuery(
            """
    SELECT DISTINCT ?predicate ?object
    WHERE
    {

      <%s> ?predicate ?object

    }
  """ % (subject_uris[i]))
        #
        sparql.setReturnFormat(JSON)
        time.sleep(2)
        final_results = sparql.query().convert()
        list_of_selected_predicates = ""
        if endpoints[i] == "https://www.ebi.ac.uk/rdf/services/sparql":
            chembl_endpoints += 1
        for result in final_results["results"]["bindings"]:
         if result["predicate"]["value"] not in non_selected_predicates:
            if validators.url(result["object"]["value"]) != True:
                words_of_one_predicate = splitter.findall(result["object"]["value"])
                words_are_mattching = set(words_of_one_predicate).intersection(
                    list_of_words_for_predicate_selection)
                if words_are_mattching and (result["predicate"]["value"] not in list_of_predicates):
                    list_of_predicates.append(result["predicate"]["value"])
        if connections[endpoints[i]] in pro.keys():
            exist_list_of_predicates = pro[connections[endpoints[i]]].split(",")
            for p in list_of_predicates:
                if p not in exist_list_of_predicates:
                    list_of_selected_predicates += p + ','
            pro[connections[endpoints[i]]] += list_of_selected_predicates
        else:
            for p in list_of_predicates:
                list_of_selected_predicates += p + ','
            pro[connections[endpoints[i]]] = list_of_selected_predicates

    class PropertyOfArray(object):

        def __init__(self, endpoint_name, refernce_name):
            self.endpoint_name = endpoint_name
            self.refernce_name = refernce_name
            self.sum_of_cosine = 0
            self.my_list = []

        def display(self):
            print "endpoint %s, varibale %s" % (self.endpoint_name, self.refernce_name)

    list_of_tuples = []
    for i in range(0, len(endpoints) - 1):
        for j in range(i + 1, len(endpoints)):
            my_tuple = (endpoints[i], endpoints[j])
            list_of_tuples.append(my_tuple)

    all_array = []

    for i in range(0, len(endpoints)):
        sparql = SPARQLWrapper(endpoints[i])

        endpoint = "%s" % endpoints[i]
        refernce = "%s" % subject_uris[i]
        myarray_of_properties = PropertyOfArray(endpoint, refernce)
        property_of_dataset = pro[connections[endpoints[i]]]
        array_property_of_dataset = property_of_dataset.split(',')
        for j in range(0, len(array_property_of_dataset)):
            sparql.setQuery(
                """
                  SELECT DISTINCT (<%s> as ?predicate) ?object
                  WHERE
                  {

                    <%s> <%s> ?object.

                  }
                """ % ((array_property_of_dataset[j]).replace(' ', ''), subject_uris[i],
                       (array_property_of_dataset[j]).replace(' ', '')))
            sparql.setReturnFormat(JSON)
            time.sleep(1)
            final_results = sparql.query().convert()
            time.sleep(1)
            for result in final_results["results"]["bindings"]:
                if validators.url(result["object"]["value"]) == True:
                    myarray_of_properties.my_list.append(
                        ([result["predicate"]["value"]], (result["object"]["value"]).rsplit('/', 1)[1]))
                else:
                    myarray_of_properties.my_list.append(([result["predicate"]["value"]], result["object"]["value"]))
        all_array.append(myarray_of_properties)

    t = 0
    final_array = {}
    no_similar = []
    for i in range(0, len(all_array) - 1):
        for j in range(i + 1, len(all_array)):
            for z in range(0, len(all_array[i].my_list)):
                for k in range(0, len(all_array[j].my_list)):
                    doc1 = all_array[i].my_list[z][1]
                    doc2 = all_array[j].my_list[k][1]

                    similarity = compare(doc1, doc2)
                    t = t + 1
                    if similarity >= 0.52:
                        if ((all_array[i].refernce_name, all_array[j].refernce_name)) in final_array.keys():
                            final_array[(all_array[i].refernce_name, all_array[j].refernce_name)] += similarity
                        else:
                            final_array[(all_array[i].refernce_name, all_array[j].refernce_name)] = similarity

    sorted_dict = sorted(final_array.iteritems(), key=operator.itemgetter(1), reverse=True)

    final_results = []
    for x in range(0, len(sorted_dict)):
        final_results.append(sorted_dict[x][0][0])
        final_results.append(sorted_dict[x][0][1])

    final_result = np.unique(final_results)
    if len(final_result) > 0:
        final = "";
        for i in range(0, len(final_result)):
            final += "%s," % (final_result)[i];
        print final[:-1]
    else:
        print "No similar items!"


# ***************************Function call**************

similarItems(my_template_id, my_subject_uris, my_endpoints, my_connections, my_for_similar)