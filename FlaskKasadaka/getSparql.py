

import urllib2
import urllib
import xml.etree.ElementTree as ET
from xml.dom import minidom
import config

def executeSparqlQuery(query, url = config.sparqlURL):
    ET.register_namespace("","http://www.w3.org/2005/sparql-results#")

    #query = urllib.quote("SELECT * WHERE {  ?sub ?pred ?obj . } LIMIT 10")

    queryHtmlFormat = urllib.quote(query)
    #requestURL = "http://cliopatria.swi-prolog.org/sparql/?query=" + query
    requestURL = url + queryHtmlFormat
    #print "requesting: "+requestURL
    resultXML = urllib2.urlopen(requestURL).read()
    #XML parsing proberen
    root = ET.fromstring(resultXML)

    head = root.find("{http://www.w3.org/2005/sparql-results#}head")
    columns = []

    for result in head:
        columns.append(result.get("name"))

    iteratorResults = root.iter(tag="{http://www.w3.org/2005/sparql-results#}result")

    results = []
    for result in iteratorResults:
        results.append([])
        for item in result:
            for content in item:
                #toAppend = urllib.quote(content.text)
                toAppend = content.text
                toAppend = toAppend.replace("-","%2D")
                results[len(results)-1].append(toAppend)

    for row in results:
        line = ""
        for entry in row:
             line += entry + "\t"
        print line

    return results


def getUsedLanguages():
    results = []
    query = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX speakle: <http://purl.org/collections/w4ra/speakle/>
PREFIX radiomarche: <http://purl.org/collections/w4ra/radiomarche/>
PREFIX lexvo: <http://lexvo.org/ontology#>

SELECT DISTINCT ?language   WHERE {
      ?voicelabel   rdfs:subPropertyOf speakle:voicelabel.
  	?voicelabel lexvo:language ?language


}"""
    output = executeSparqlQuery(query)
    #get the language code behind the last slash
    for string in output:
        results.append(string[0].rsplit('/', 1)[-1])

    return set(results)
