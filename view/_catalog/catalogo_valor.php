<div id="content">
    <div id="insertarCatalogoValor">
     
       <form action="crud.php" name="form_valor" method="post">
         <input type="hidden" name="action" value="insertCatalogoValor" />
              <p align="center"id="mensaje"> <strong> Desea insertar un nuevo valor: </strong></p> 
       <p align="center">
         <strong>Nombre:</strong> <input type="text" name="catalogoNombre"/>
                           <strong>Catalogo:</strong><select name="id_cat">
                               <option selected value="blanco">Seleccione una opcion</option>
        	        <?php foreach($vars['catalogos'] as $cat) { ?>
                     <option value="<?php echo $cat->id; ?>"><?php echo $cat->nombre; ?></option>                      
                                                         <?php } ?>
                                                    </select>                              
                           <strong>Depende:</strong><select name="id_catalogo">
                        <option selected value="blanco">Seleccione una opcion</option>
        	<?php foreach($vars['valores'] as $catalogos) { ?>
  
  <option value="<?php echo $catalogos->id; ?>"><?php echo $catalogos->nombreValor.','.$catalogos->nombreCatalogo; ?></option>                      
            <?php } ?>
                          </select>        
            
         </p>
           <p align="center"><input name="aceptarButton" type="submit" value="Aceptar" /> </p>  
           
      </form>    
    </div>
    <div id= "lista catalogos_valor">
     <?php if(count($vars['cat_valores'])==0) echo "No hay catalogos"; ?>
     <?php foreach($vars['cat_valores'] as $cat_v) { ?> 
     
                                                                                              
    </div>
    
         <div id="catalogos_valores" class="UserPar">
           
             <div id="showCatalogoValores<?php echo $cat_v->id;?>">
                <div class="divRight">
                    <p><strong>Id:</strong>:  <?php echo $cat_v->id; ?>    
                       
                       <strong>Nombre:</strong>: <?php echo $cat_v->valor; ?>   
                                                                 
                       <strong>Depende:</strong>: <?php $id= $cat_v->id; $fuente = $_CATALOGS->getValueRecurs($id); echo $fuente; ?>                                         
                       
                       <strong>Catalogo:</strong>: <?php echo $cat_v->nombreCatalogo; ?> <br />  
                     </p>   
                      
              </div>   
             <ul id="menuItem">                 
                    <li><a href="#" class="editLink" name="CatalogoValor<?php echo $cat_v->id; ?>">editar</a>
                    <a class="deleteLink" href="crud.php?action=eliminarCatalogoValor&id=<?php echo $cat_v->id; ?>">eliminar</a></li>
             </ul>
           <div id="editCatalogoValor<?php echo $cat_v->id; ?>" class="UserPar" style="display:none;">
                   <form action="crud.php" method="post">
				           <input type="hidden" name="action" value="updateCatalogoValor"/>
                           <input type="hidden" name="id" value="<?php echo $cat_v->id; ?>" />	
                           
                           <div class="divRight">
                            <p><strong>Nombre:</strong>:  <input name="nombre" type="text" value= "<?php echo $cat_v->valor;?>"/>
                                <strong>Catalogo:</strong>:<select name="id_cat"> 
                                                     <option selected value="<?php echo $cat_v->idCatalogo ?>">Seleccione una opcion</option>
        	                                           <?php foreach($vars['catalogos'] as $catalogos) { ?>
                                          <option value="<?php echo $catalogos->id; ?>"><?php echo $catalogos->nombre; ?></option>                      
                                                                                               <?php } ?>
                                                      </select>                             
                             
                               <strong>Depende:</strong>:<select name="id_depende">
                                                     <option selected value="<?php if(($cat_v->idDepende !='')&&($cat_v->idDepende !=0)) echo $cat_v->idDepende;else echo 'blanco'; ?>">                                                     Seleccione una opcion</option>
        	                                         <?php foreach($vars['valores'] as $catalogos) { ?>
  
                                          <option value="<?php echo $catalogos->id; ?>"><?php echo $catalogos->nombreValor.','.$catalogos->nombreCatalogo; ?></option>                      
                                                                                               <?php } ?>
                                                      </select>
                                                   
                              <br />                                                              
                              </p>
                	      </div>
                          <div id="menuButtons">
                            <input name="" type="submit" value="Guardar" />
                            <input name="CatalogoValor<?php echo $cat_v->id; ?>" type="button" value="Cancelar" class="showButton" />
                	     </div>
                   </form>            
             </div>                    
           
                                            <?php }?>
         </div>
    



</div>     
</div>
    