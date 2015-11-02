

import urllib2
import urllib
import xml.etree.ElementTree as ET


ET.register_namespace("","http://www.w3.org/2005/sparql-results#")


#query = urllib.quote("SELECT * WHERE {  ?sub ?pred ?obj . } LIMIT 10")

#fetch 5 persons to choose from
queryGivePersons = urllib.quote("""PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT DISTINCT * WHERE {
  ?person
    rdf:type ns1:Person  .
  ?person ns1:contact_fname ?fname .
  ?person ns1:contact_lname ?lname
}
LIMIT 5""")



#requestURL = "http://cliopatria.swi-prolog.org/sparql/?query=" + query
requestURL = "http://192.168.3.14:3020/sparql/?query=" + queryGivePersons
resultXML = urllib2.urlopen(requestURL).read()
#XML parsing proberen
root = ET.fromstring(resultXML)

#print "PARSED RESULT   \n" +ET.tostring(root)



head = root.find("{http://www.w3.org/2005/sparql-results#}head")
columns = []

for result in head:
    columns.append(result.get("name"))

iteratorResults = root.iter(tag="{http://www.w3.org/2005/sparql-results#}result")

table = [columns]
for result in iteratorResults:
    table.append([])
    for item in result:
       for content in item:
           table[len(table)-1].append(content.text)

##print resultaat
for row in table:
    line = ""
    for entry in row:
        line += entry + "\t"
    print line




#print "requested: " + requestURL + "\n"
