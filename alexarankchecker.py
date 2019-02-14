
#!/usr/bin/env python
# chmod +x alexarankcheck.py
# pyhon alexarankcheck.py codelatte.org

import urllib, sys, re
xml = urllib.urlopen('http://data.alexa.com/data?cli=10&dat=s&url=%s'%sys.argv[1]).read()
try: rank = int(re.search(r'<POPULARITY[^>]*TEXT="(\d+)"', xml).groups()[0])
except: rank = -1
try: country = int(re.search(r'<COUNTRY[^>]*RANK="(\d+)"', xml).groups()[0])
except: country = -1
print 'Global rank for %s is %d!' % (sys.argv[1], rank)
print 'Country rank for %s is %d!\n' % (sys.argv[1], country)
