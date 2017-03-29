#!/usr/bin/env python
from SPARQLWrapper import SPARQLWrapper, JSON
import ast
import re, math
from collections import Counter
import porter
import validators
import numpy as np
from numpy import zeros,dot
from numpy.linalg import norm
from more_itertools import unique_everseen
import operator
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




__all__=['compare']

# import real stop words
stop_words = [ 'i', 'in', 'a', 'to', 'the', 'it', 'have', 'haven\'t', 'was', 'but', 'is', 'be', 'from' ]
#stop_words = []
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
 
 
#***************************************


#Test, targets, 
#template="3"
#Test1, slicni su http://chem2bio2rdf.org/kegg/resource/kegg_ligand/C00018 i http://chem2bio2rdf.org/pdb/resource/pdb_ligand/PLP
#endpoints=['http://cheminfov.informatics.indiana.edu:8080/kegg/sparql','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql','http://cheminfov.informatics.indiana.edu:8080/pdb/sparql']
#subject_uris=['http://chem2bio2rdf.org/kegg/resource/kegg_ligand/C00018','http://chem2bio2rdf.org/bindingdb/resource/bindingdb_ligand/10','http://chem2bio2rdf.org/pdb/resource/pdb_ligand/PLP']
#connections={'http://cheminfov.informatics.indiana.edu:8080/kegg/sparql':'Kegg/Chem2Bio2RDF','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql':'BindingDB/Chem2Bio2RDF','http://cheminfov.informatics.indiana.edu:8080/pdb/sparql':'PDB/Chem2Bio2RDF'}


#Test za rad:
#template="2"
#endpoints=['http://cpctas-lcmb.pmf.kg.ac.rs:2020/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql']
#subject_uris=['http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#TaregtTest1','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL2208', 'http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL3587','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL4040','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL614245','http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/55299']
#connections={'http://cpctas-lcmb.pmf.kg.ac.rs:2020/sparql':'PIBAS/CPCTAS','https://www.ebi.ac.uk/rdf/services/chembl/sparql':'Chembl/EMBL-EBI','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql':'BindingDB/Chem2Bio2RDF'}


#Test za rad 1
#endpoints=['http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql']
#subject_uris=['http://bio2rdf.org/drugbank:BE0000045','http://bio2rdf.org/drugbank:BE0000092', 'http://bio2rdf.org/drugbank:BE0000560','http://bio2rdf.org/drugbank:BE0000247','http://bio2rdf.org/drugbank:BE0000405','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL5763','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL612545','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL220','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL4768']
#connections={'https://www.ebi.ac.uk/rdf/services/chembl/sparql':'Chembl/EMBL-EBI','http://drugbank.bio2rdf.org/sparql':'Drugbank/Bio2RDF'}
#MJFJKKXQDNNUJF-UHFFFAOYSA-N, Similar items are: http://bio2rdf.org/drugbank:BE0000045 and http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL5763


#endpoints=['http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql']
#subject_uris=['http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/50140','http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/50141', 'http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/50142','http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/50143']
#connections={'http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql':'BindingDB/Chem2Bio2RDF'}
#QAOWNCQODCNURD-UHFFFAOYSA-L, Similar items are: all


#endpoints=['http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql']
#subject_uris=['http://bio2rdf.org/drugbank:BE0000379','http://bio2rdf.org/drugbank:BE0000379', 'http://bio2rdf.org/drugbank:BE0000379','http://bio2rdf.org/drugbank:BE0000379']
#connections={'http://drugbank.bio2rdf.org/sparql':'Drugbank/Bio2RDF'}
#PMATZTZNYRCHOR-IMVLJIQENA-N, Similar items are:all



#endpoints=['https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql']
#subject_uris=['http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL1743128','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL5269', 'http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL5653','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL612545']
#connections={'https://www.ebi.ac.uk/rdf/services/chembl/sparql':'Chembl/EMBL-EBI'}
#VPLAJGAMHNQZIY-ZBRFXRBCSA-N, similar are:

template="2"
endpoints=['http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql','https://www.ebi.ac.uk/rdf/services/chembl/sparql']
subject_uris=['http://bio2rdf.org/drugbank:BE0000268','http://bio2rdf.org/drugbank:BE0000292','http://bio2rdf.org/drugbank:BE0000331','http://bio2rdf.org/drugbank:BE0000617','http://bio2rdf.org/drugbank:BE0000734','http://bio2rdf.org/drugbank:BE0000822','http://bio2rdf.org/drugbank:BE0000933','http://bio2rdf.org/drugbank:BE0001240','http://bio2rdf.org/drugbank:BE0002154','http://bio2rdf.org/drugbank:BE0002176','http://bio2rdf.org/drugbank:BE0002177','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL5441','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL2890','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL3171','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL5328','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL6137','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL1952','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL386','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL612545','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL382','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL1939','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL1075051','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL2902','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL614320','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL614177','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL387','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL375','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL612544']
connections={'https://www.ebi.ac.uk/rdf/services/chembl/sparql':'Chembl/EMBL-EBI','http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql':'BindingDB/Chem2Bio2RDF','http://drugbank.bio2rdf.org/sparql':'Drugbank/Bio2RDF'}


#endpoints=['http://cpctas-lcmb.pmf.kg.ac.rs:3030/PIBAS/query','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql','http://drugbank.bio2rdf.org/sparql']
#subject_uris=['http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#TargetTest2','http://bio2rdf.org/drugbank:BE0000320','http://bio2rdf.org/drugbank:BE0000734','http://bio2rdf.org/drugbank:BE0000763','http://bio2rdf.org/drugbank:BE0000777','http://bio2rdf.org/drugbank:BE0002175','http://bio2rdf.org/drugbank:BE0002176']
#connections={'http://cpctas-lcmb.pmf.kg.ac.rs:3030/PIBAS/query':'PIBAS/CPCTAS','http://drugbank.bio2rdf.org/sparql':'Drugbank/Bio2RDF'}
#MSTNYGQPCMXVAQ-KIYNQFGBSA-N





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
 """% template)



sparql.setReturnFormat(JSON)
final_results= sparql.query().convert()

for result in final_results["results"]["bindings"]:
 electedproperties=result["electedproperties"]["value"] 
 #print electedproperties


datasets=connections.values()
for i in range(0,len(datasets)):
 #print electedproperties
 pro=ast.literal_eval(electedproperties)
 #print pro[datasets[i]]




class PropertyOfArray(object):

 def __init__(self,endpoint_name,refernce_name):
  self.endpoint_name=endpoint_name
  self.refernce_name=refernce_name 
  self.sum_of_cosine=0
  self.my_list=[]

 def display(self):
  print "endpoint %s, varibale %s" % (self.endpoint_name,self.refernce_name)





all_array=[]

for i in range(0,len(endpoints)):
 sparql = SPARQLWrapper(endpoints[i])
 #print endpoints[i]
 endpoint="%s" % endpoints[i]
 refernce="%s" % subject_uris[i]
 myarray_of_properties=PropertyOfArray(endpoint,refernce)
 #myarray_of_properties.display()


 property_of_dataset=pro[connections[endpoints[i]]]
 array_property_of_dataset=property_of_dataset.split(',')
 for j in range(0,len(array_property_of_dataset)): 
  #print array_property_of_dataset
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
  for result in final_results["results"]["bindings"]:
   print result["predicate"]["value"],result["object"]["value"]
   if validators.url(result["object"]["value"])==True:
    myarray_of_properties.my_list.append(([result["predicate"]["value"]],(result["object"]["value"]).rsplit('/',1)[1]))
   else:
    myarray_of_properties.my_list.append(([result["predicate"]["value"]],result["object"]["value"]))
 print "________________________________________"
 


 all_array.append(myarray_of_properties)
 



list_of_tuples=[]
list_of_subject=[]

for i in range(0,len(endpoints)-1):
 for j in range (i+1,len(endpoints)):
  my_tuple=(endpoints[i],endpoints[j])
  my_tuple_of_subject=(subject_uris[i],subject_uris[j])
  list_of_tuples.append(my_tuple)
  list_of_subject.append(my_tuple_of_subject)

#print list_of_tuples
#print list_of_subject


final_array={}
for i in range(0,len(all_array)-1):
 for j in range(i+1,len(all_array)):
  for z in range(0,len(all_array[i].my_list)):
    #vector1 = text_to_vector(all_array[i].my_list[z][1])
    for k in range(0,len(all_array[j].my_list)):
     #vector2 = text_to_vector(all_array[j].my_list[k][1])
     doc1=all_array[i].my_list[z][1]
     doc2=all_array[j].my_list[k][1]
     #print "Using Doc1: %s\n\nUsing Doc2: %s\n" % ( doc1, doc2 )
     #print "Similarity %s" % compare(doc1.lower(),doc2.lower())
     similarity=compare(doc1.lower(),doc2.lower())
     #print similarity
     #print all_array[i].my_list[z][1],similarity,all_array[j].my_list[k][1],'\n'   
     if (similarity>=0.7 and similarity<=1.1):
        #print all_array[i].my_list[z][1],similarity,all_array[j].my_list[k][1]
        #print "(URI-%s**%s,URI-%s:**%s,%f)" % (all_array[i].refernce_name,all_array[i].my_list[z][1],all_array[j].refernce_name,all_array[j].my_list[k][1],similarity)
        if (all_array[i].refernce_name,all_array[i].refernce_name) in final_array:
         final_array[(all_array[i].refernce_name,all_array[i].refernce_name)]+=similarity
        else:
         final_array[(all_array[i].refernce_name,all_array[j].refernce_name)]=similarity
		
sorted_dict =sorted(final_array.iteritems(), key=operator.itemgetter(1), reverse=True)

		 
#print sorted_dict		 

final_results=[]
for x in range(0,len(sorted_dict)):
 final_results.append(sorted_dict[x][0][0])
 final_results.append(sorted_dict[x][0][1])
 
 
#print type(final_results)
final_result=np.unique(final_results)

if len(final_result)>0:
 print "Similar items are: "
 for i in range(0,len(final_result)):
  print "%s" % (final_result)[i]
else:
 print "No similar items!"