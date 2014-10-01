CC=gcc
CFLAGS= -Wall
LIBS=-lgd -lpng -lm -lz 

all:	 userdist.cgi moviedist.cgi	
	chmod 755 *.cgi

USERDISTOS=userdistcgi.o table.o vector.o cgiparse.o utils.o

userdist.cgi: $(USERDISTOS)
	$(CC) $(CFLAGS) $(LIBS) $(USERDISTOS) -o userdist.cgi

MOVIEDISTOS=moviedistcgi.o table.o vector.o cgiparse.o utils.o

moviedist.cgi: $(MOVIEDISTOS)
	$(CC) $(CFLAGS) $(LIBS) $(MOVIEDISTOS) -o moviedist.cgi

*.c.o:
	$(CC) $(CFLAGS) -c $<

clean:
	rm *.o *.cgi core
