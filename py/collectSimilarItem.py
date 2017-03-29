#!/usr/bin/env python
from SPARQLWrapper import SPARQLWrapper, JSON
import ast
import re
import math
import operator
import porter
import validators
import numpy as np
from numpy import zeros,dot
from numpy.linalg import norm
from collections import Counter
import sys

import ssl
ssl._create_default_https_context = ssl._create_unverified_context

#***************************Find most similar properties****************


WORD = re.compile(r'\w+')

def get_cosine(vec1, vec2):
     intersection = set(vec1.keys()) & set(vec2.keys())
     numerator = sum([vec1[x] * vec2[x] for x in intersection])

     sum1 = sum([vec1[x]**2 for x in vec1.keys()])
     sum2 = sum([vec2[x]**2 for x in vec2.keys()])
     denominator = math.sqrt(sum1) * math.sqrt(sum2)

     if not denominator:
        return 0.0
     else:
        return float(numerator) / denominator

def text_to_vector(text):
    words = WORD.findall(text)
    return Counter(words)




# import real stop words
stop_words = [ 'i', 'in', 'a', 'to', 'the', 'it', 'have', 'haven\'t', 'was', 'but', 'is', 'be', 'from' ]
#print stop_words

splitter=re.compile ( "[a-z0-9]+", re.I )
stemmer=porter.PorterStemmer()

def add_word(word,d):
 """
    Adds a word the a dictionary for words/count
    first checks for stop words
	the converts word to stemmed version
 """
 w=word.lower() 
 if w not in stop_words:
  ws=stemmer.stem(w,0,len(w)-1)
  d.setdefault(ws,0)
  d[ws] += 1

def doc_vec(doc,key_idx):
 v=zeros(len(key_idx))
 for word in splitter.findall(doc):
  keydata=key_idx.get(stemmer.stem(word,0,len(word)-1).lower(), None)
  if keydata: v[keydata[0]] = 1
 
 return v


def compare(doc1,doc2):

 # strip all punctuation but - and '
 # convert to lower case
 # store word/occurance in dict
 all_words=dict()

 for dat in [doc1,doc2]:
  [add_word(w,all_words) for w in splitter.findall(dat)]
 
 # build an index of keys so that we know the word positions for the vector
 key_idx=dict() # key-> ( position, count )
 keys=all_words.keys()
 keys.sort()
 #print keys
 for i in range(len(keys)):
  key_idx[keys[i]] = (i,all_words[keys[i]])
 del keys
 del all_words
 #print doc1,doc2
 v1=doc_vec(doc1,key_idx)
 v2=doc_vec(doc2,key_idx)
 #print v1,v2
 return (dot(v1,v2) / (norm(v1) * norm(v2)))
 


#*****************************Data from php page*********************


my_file=sys.argv[1]
#my_file="../txt/testfile_2_AAAAKTROWFNLEP-UHFFFAOYSA-N.txt";
fp=open(my_file,"r")

lines = [line.rstrip('\n') for line in fp]

my_template_id=lines[0]
my_subject_uris=lines[1].split(',')
my_endpoints=lines[2].split(',')
my_connections=dict(x.split('=') for x in (lines[3]).split(','))
my_for_similar=lines[4]

#print my_template_id
#print my_subject_uris
#print my_endpoints
#print my_connections
#print my_for_similar

#test values
#my_template_id=2;
#my_subject_uris=['http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#TargetTest1','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL2208','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL3587','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL4040','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL614245','http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/55299'];
#my_endpoints=['http://cpctas-lcmb.pmf.kg.ac.rs:3030/PIBAS/query','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql'];
#my_connections={'http://cpctas-lcmb.pmf.kg.ac.rs:3030/PIBAS/query':'PIBAS/CPCTAS','https://www.ebi.ac.uk/rdf/services/chembl/sparql':'Chembl/EMBL-EBI','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql':'BindingDB/Chem2Bio2RDF'};
#my_for_similar='no';
#*************************Function for finding most similar items****
def similarItems(template_id,subject_uris,endpoints,connections,for_similar):
    
    
    sparql = SPARQLWrapper("http://cpctas-lcmb.pmf.kg.ac.rs:2020/sparql")


    sparql.setQuery(
     """  
       PREFIX pibas: <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
       SELECT ?electedproperties
       FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
       WHERE 
       {
           ?template pibas:id ?templateID.
           ?template pibas:electProperties ?electedproperties.
           FILTER(?templateID=%s).

        }
     """% template_id)


    sparql.setReturnFormat(JSON)
    final_results= sparql.query().convert()
    for result in final_results["results"]["bindings"]:
     if (for_similar=="no"):
      electedproperties=ast.literal_eval(result["electedproperties"]["value"])
     else:
      get_electedproperties=ast.literal_eval(result["electedproperties"]["value"])      
      new_similar_properties=for_similar.split('=>')
      new_propeties = {}
      for i in range(0,len(new_similar_properties)):
       
       if (i%2==0):
        new_propeties [(new_similar_properties[i]).strip('"')]=(new_similar_properties[i+1]).strip('"')
      
      electedproperties=get_electedproperties.copy()
      electedproperties.update(new_propeties)
      #print electedproperties
     
    
    datasets=connections.values()
    
    for i in range(0,len(datasets)):
     pro=electedproperties
     #print electedproperties[datasets]
     
    #print pro
    #pro={'PIBAS/CPCTAS': 'http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#hasSynonym,http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#hasTargetName', 'Drugbank/Bio2RDF': 'http://purl.org/dc/terms/title,http://www.w3.org/2000/01/rdf-schema#label', 'TestDataset/TestInitiative': 'http://147.91.205.66:2020/Tests/TestOntology#hasSynonym,http://147.91.205.66:2020/Tests/TestOntology#hasName', 'Chembl/EMBL-EBI': 'http://www.w3.org/2000/01/rdf-schema#label', 'BindingDB/Chem2Bio2RDF': 'http://chem2bio2rdf.org/bindingdb/resource/TARGET'}



    class PropertyOfArray(object):

     def __init__(self,endpoint_name,refernce_name):
      self.endpoint_name=endpoint_name
      self.refernce_name=refernce_name 
      self.sum_of_cosine=0
      self.my_list=[]

     def display(self):
      print "endpoint %s, varibale %s" % (self.endpoint_name,self.refernce_name)


    
    list_of_tuples=[]
    for i in range(0,len(endpoints)-1):
     for j in range (i+1,len(endpoints)):
      my_tuple=(endpoints[i],endpoints[j])
      list_of_tuples.append(my_tuple)

    #print list_of_tuples
    
   

    all_array=[]

    for i in range(0,len(endpoints)):
     #print endpoints[i]
     sparql = SPARQLWrapper(endpoints[i])
     
     endpoint="%s" % endpoints[i]
     refernce="%s" % subject_uris[i]
     myarray_of_properties=PropertyOfArray(endpoint,refernce)
     #myarray_of_properties.display()
     property_of_dataset=pro[connections[endpoints[i]]]
     array_property_of_dataset=property_of_dataset.split(',')
     for j in range(0,len(array_property_of_dataset)): 
      #array_property_of_dataset[j]
      sparql.setQuery(
      """  
        SELECT DISTINCT (<%s> as ?predicate) ?object
        WHERE 
        {
         
          <%s> <%s> ?object
          
        }
      """% (array_property_of_dataset[j],subject_uris[i],array_property_of_dataset[j])) 
      sparql.setReturnFormat(JSON)
      final_results= sparql.query().convert()
      #print final_results
      for result in final_results["results"]["bindings"]:
       #print result["predicate"]["value"],result["object"]["value"]
       if validators.url(result["object"]["value"])==True:
        myarray_of_properties.my_list.append(([result["predicate"]["value"]],(result["object"]["value"]).rsplit('/',1)[1]))
       else:
        myarray_of_properties.my_list.append(([result["predicate"]["value"]],result["object"]["value"]))
       #print "________________________________________"



     all_array.append(myarray_of_properties)

     


    

    final_array={}
    
    for i in range(0,len(all_array)-1):
     for j in range(i+1,len(all_array)):
      for z in range(0,len(all_array[i].my_list)):
        for k in range(0,len(all_array[j].my_list)):
         doc1=all_array[i].my_list[z][1]
         doc2=all_array[j].my_list[k][1]
         similarity=compare(doc1.lower(),doc2.lower())
         if (similarity>=0.7 and similarity<=1.1):
            #print "(Ontology-%s**%s,Ontology-%s:**%s,%f)" % (all_array[i].endpoint_name,all_array[i].my_list[z][1],all_array[j].endpoint_name,all_array[j].my_list[k][1],cosine)
          if (all_array[i].refernce_name,all_array[i].refernce_name) in final_array:
	   final_array[(all_array[i].refernce_name,all_array[i].refernce_name)]+=similarity
	  else:
	   final_array[(all_array[i].refernce_name,all_array[j].refernce_name)]=similarity

    sorted_dict=sorted(final_array.iteritems(), key=operator.itemgetter(1), reverse=True)
	 

    final_results=[]
    for x in range(0,len(sorted_dict)):
     final_results.append(sorted_dict[x][0][0])
     final_results.append(sorted_dict[x][0][1])

    
    #print type(final_results)
    final_result=np.unique(final_results)
    if len(final_result)>0:
     final="";
     for i in range(0,len(final_result)):
      final+="%s," % (final_result)[i];
     print final[:-1]
    else:
     print "No similar items!"

#***************************Function call**************

similarItems(my_template_id,my_subject_uris,my_endpoints,my_connections,my_for_similar)