#include <pthread.h>

#ifndef _VECTOR_H
#define _VECTOR_H

typedef struct
{
  void **v;
  int allocated;
  int size;
  int allocationchunksize;
  
  pthread_mutex_t mutex;
} VectorRecord;

typedef VectorRecord *Vector;

Vector Vector_create(int allocationchunksize);
int Vector_grow(Vector v);
int Vector_add(Vector v, void* p);
void* Vector_elementAt(Vector v, int index);
int Vector_setElementAt(Vector v, int index, void* p);
int Vector_getSize(Vector v);
void Vector_free(Vector v);
int Vector_delete(Vector v, int index);
int Vector_find(Vector v, void *p);
int Vector_finds(Vector v, char *s);
void Vector_sort(Vector v, int (*compar)(const void *, const void *));

#endif

