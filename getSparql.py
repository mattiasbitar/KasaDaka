

import urllib2
import urllib
import xml.etree.ElementTree as ET
from xml.dom import minidom


def prettify(elem):
    #"""Return a pretty-printed XML string for the Element. """
    rough_string = ET.tostring(elem, 'utf-8')
    reparsed = minidom.parseString(rough_string)
    return reparsed.toprettyxml(indent="  ")




def executeSparqlQuery(query, url):
    ET.register_namespace("","http://www.w3.org/2005/sparql-results#")

    #query = urllib.quote("SELECT * WHERE {  ?sub ?pred ?obj . } LIMIT 10")

    queryHtmlFormat = urllib.quote(query)
    #requestURL = "http://cliopatria.swi-prolog.org/sparql/?query=" + query
    requestURL = url + queryHtmlFormat
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
        #results.append([])
        line = ""
        for item in result:
            for content in item:
                #when there are more than 2 columns, add them seperated by a space
                if line != "":
                    line += " "
                line += content.text
        results.append(line)

    return results

def createVoiceXML(  questionText, choices,  query = ""):

    outputRoot = ET.Element("vxml")
    outputRoot.set("version","2.0")
    outputRoot.set("lang","en")

    comment = ET.Comment('Generated from sparql query:\n' + query + "\n")
    outputRoot.append(comment)

    formElement = ET.Element("form")
    outputRoot.append(formElement)

    fieldElement = ET.Element("field")
    fieldElement.set("name","choice")
    outputRoot.append(fieldElement)

    questionElement = ET.SubElement(fieldElement,"prompt")
    questionElement.text = question

    for choice in choices:
        optionElement = ET.SubElement(fieldElement,"option")
        optionElement.text = choice

    return prettify(outputRoot)

question = "Dit is de vraag die bij de query hoort"


queryReadable = """PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    SELECT DISTINCT ?fname ?lname WHERE {
    ?person
    rdf:type ns1:Person  .
    ?person ns1:contact_fname ?fname .
      ?person ns1:contact_lname ?lname
    }
    LIMIT 5"""

sparqlURL = "http://192.168.3.13:3020/sparql/?query="



print createVoiceXML( question,executeSparqlQuery(queryReadable, sparqlURL),queryReadable)

#print "requested: " + requestURL + "\n"
