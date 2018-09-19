#!/usr/bin/python
# -*- coding: utf-8 -*-

import requests
from bs4 import BeautifulSoup

url = raw_input("URL: ")
r = requests.get(url)
html_as_string = r.text
soup = BeautifulSoup(html_as_string, 'html.parser')
for link in soup.find_all('a'):
    print(link.get('href'))
