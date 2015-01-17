<?php
try{
    $coneccion = new Mongo();
    $db = $coneccion->selectDB('miblog');
    $collection = $db->selectCollection('articulos');
    $articulo = array();
    //guardar($collection);
    if ($_GET){
        recuperarUno($collection);
    }else{
        recuperar($collection);
    }
    
    $coneccion->close();
} catch (MongoConnectionException $e) {
    die('No se pudo conectar la BD '.$e->getMessage());
} catch (MongoException $e){
    die ('No se pudo guardar los datos '.$e->getMessage());
}


function guardar($collection){
    $articulo = array();
    $articulo['titulo']='Titulo'.  time();
    $articulo['contenido']='Contenido' . time();
    $articulo['guardado_el']=new MongoDate();
    try{
        //$collection->insert($articulo);
        $resultado = $collection->insert($articulo,array('safe'=>True));
        //$resultado = $collection->insert($articulo,array('safe'=>True,'timeout'=>True));
        print_r($resultado);
    }  catch (MongoCursorException $e){
        die('Insert ha fallado '.$e->getMessage());
    } catch (MongoCursorTimeoutException $e){
        die('Tiempo de espera para Insert ha finalizado '.$e->getMessage());
    }
    return $resultado;
}

function recuperar($collection){
    try{
        $cursor = $collection->find();
    } catch (Exception $e) {
        die ($e->getMessage());
    }
    
/*   if(!$cursor->count()===0){
 *         foreach ($cursor as $articulo){
 *            echo '<a href=mongodb.php?id='.$articulo['_id'].'><b>'.$articulo['titulo'].'</b></a>';
 *            echo '<br>';
 *            echo $articulo['contenido'];
 *            echo '<br>';
 *            echo $articulo['guardado_el'];
 *            echo '<br>';
 *        }
 *   }
 */
    
/*  $array = iterator_to_array($cursor);
    if(!empty($array)){
        foreach ($array as $item){
            
        }
    } */
    
    while ($cursor->hasNext()):
        $articulo = $cursor->getNext();
        echo '<a href=mongodb.php?id='.$articulo['_id'].'><b>'.$articulo['titulo'].'</b></a>';
        echo '<br>';
        echo $articulo['contenido'];
        echo '<br>';
        echo $articulo['guardado_el'];
        echo '<br>';
    endwhile;
}

function recuperarUno($collection){
    $id = $_GET['id'];
    $articulo = $collection->findOne(array('_id'=>new MongoId($id)));
    echo '<b>'.$articulo['titulo'].'</b>';
    echo '<br>';
    echo $articulo['contenido'];
    echo '<br>';
    echo $articulo['guardado_el'];
    echo '<br>';

}

$collection->find(array('x'=>array('$gt'=>100))); // x mayor que 100
$collection->find(array('x'=>array('$lt'=>100))); // x menor que 100
$collection->find(array('x'=>array('$gte'=>100))); // x mayor o igual que 100
$collection->find(array('x'=>array('$lte'=>100))); // x menor o igual que 100
$collection->find(array('x'=>array('$gte'=>100,'$lte'=>200))); //entre 100 y 200
$collection->find(array('x'=>array('$ne'=>100))); //distinto de 100

/* Recupero solo algunos campos 
 * el primer array es para la consulta y como esta vacia traerá todos los registros
 * el segundo array es para indicar que campos quiero que traiga */
$cursor = $collection->find(array(), array('titulo','guardado_el'));
/* Ordenamos el cursor por fecha en forma descendente(-1) o ascendente(1)*/
$cursor->sort(array('guardado_el'=> -1));
//$cursor->sort(array('guardado_el'=> -1,'titulo'=>1)); //ordeno por dos campos
/* con skip salto un num determinado de registros
 * con limit indico cuantos articulos recuperar */
$cursor->sort(array('guardado_el'=> -1))->skip($num)->limit($numLimit); 
$cursor->limit(10);//Muestra solo los primeros 10

/*$ultimaSemana = new MongoDate(strtotime('-1 week'));
$cursor = $collection->find(array('guardado_el'=>array('$gt'=>$ultimaSemana)));*/

$inicio = new MongoDate(strtotime('2014-03-08 00:00:00'));
$fin = new MongoDate(strtotime('2014-03-10 23:00:00'));
$cursor = $collection->find(array('guardado_el'=>array('$gte'=>$inicio, '$lte'=>$fin)));

/*UPDATE */
$collection->update(array('_id'=> new MongoId($id)), $articulo);
/* Tambien se puede usar el array('safe'=>True)
 * $collection->update(array('_id'=> new MongoId($id)), $articulo, array('safe'=>True));
 * Para que se puedan actualizar varios articulos
 * * $collection->update(array('_id'=> new MongoId($id)), $articulo, array('multiple'=>True));
 * $collection->update(array('_id'=> new MongoId($id)), $articulo, array('safe'=>True, 'timeout'=>100));
 * upsert(update si existe, sino insert)
 * $collection->update(array('_id'=> new MongoId($id)), $articulo, array('upsert'=>True)); 
 */

/*Save en lugar de Update
$documento = array('nombre'=>'Nombre','edad'=>43);
$collection->save($documento); //inserta
$documento['edad']=44; 
$collection->save($documento); //actualiza
*/

/* Modificador $set
 * $articulos->update(array('_id'=>new mongoId('354swd342343')),array($set=>array('title'=>'Nuevo Titulo')));
 */

/* Modificador $inc
Incrementa el valor de un campo en una cantidad especifica        
$articulos->update(array('_id'=>new mongoId('354swd342343')),
        array($set=>array('title'=>'Nuevo Titulo'),
            '$inc'=>array('update_count'=>1)));
 * */

/* Modificador $unset
elimina el contenido de un campo. Como los campos solo existen cuando tienen dato
 * es como eliminar un campo.     
$articulos->update(array('_id'=>new mongoId('354swd342343')),
        array($unset=>array('title'=>True)));
*/

/* Modificador $rename
 * Cambia el nombre de un campo
$articulo->update(array(),
        array($rename=>array('guardado_el'=>'fecha_guardado')),
        array('multiple'=>True));
*/

/* Borrar Registro 
*  $articulo->remove(array('_id'=>new MongoId($id)));
 * Con safe espera la respuesta de la bd a la operacion de borrado.
 * $articulo->remove(array('nombre'=>'Juan'),
 *      array('safe'=>True));
 * Con timeout le damos un tiempo máx de espera a la operacion
 * $articulo->remove(array('nombre'=>'Juan'),
 *      array('safe'=>True,'timeout'=>200));
 * Con justone borra el primer doc que se corresponda con la consulta
 * $articulo->remove(array('nombre'=>'Juan'),
 *      array('justone'=>True));
 */