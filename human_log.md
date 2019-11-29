# Notas de diseño y desarrollo de KFP Vota Ideas

## Requisitos MVP (producto mínimo viable)

* Los visitante del blog pueden proponer ideas para ser votadas.
* Los visitantes del blog pueden ver la lista de ideas propuestas
* Los visitantes del blog pueden votar las ideas
* Cada vez que un visitante vota una idea se incrementa el contador de votos de esa idea

## Agosto 2019

Como vengo del mundo de Juan Palomo al principio he pensado crear dos tablas para implementar las dos entidades principales de este plugin: votos e ideas.

## 17 Octubre 2019

He pensado que es mejor utilizar Custom Post Type para implementar las entidades, ya que las ideas son pequeños posts y los votos casi que también porque podrían incorporar comentarios a las ideas. 

Esta implementación es la que he puesto en práctica y ha funcionado, pero finalmente estoy pensando que sería mejor cambiar los votos por comentarios, creo que puedo aprovechar algunas características para ahorrar trabajo y me parece más coherente.

```php
<?php
// Esto es código 
$variable = "Esto es genial"
function hazAlgoBien()
{

}
```
