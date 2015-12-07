

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
                toAppend = urllib.quote_plus(content.text)
                toAppend = toAppend.replace("-","%2D")
                results[len(results)-1].append(toAppend)

    for row in results:
        line = ""
        for entry in row:
             line += entry + "\t"
        print line





    return results
