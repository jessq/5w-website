#ifndef _VECTOR_H
#define _VECTOR_H

typedef struct
{
  char **v;
  int allocated;
  int size;
  int allocationchunksize;
} VectorRecord;

typedef VectorRecord *Vector;

Vector Vector_create(int allocationchunksize);
int Vector_grow(Vector v);
int Vector_add(Vector v, char* p);
char* Vector_elementAt(Vector v, int index);
int Vector_setElementAt(Vector v, int index, char* p);
int Vector_getSize(Vector v);
void Vector_free(Vector v);
int Vector_delete(Vector v, int index);

#endif

