<script type="text/javascript">
 $(document).ready(function(){
	canislib.showEdit.init();
 });
 </script>
<div id="content">
    <div id="insertarCatatalogo" class="post">
      <p align="center">
       <form action="crud.php" method="post">
         <input type="hidden" name="action" value="insertCatalogo" />
              <p align="center" id="mensaje"> <strong> Desea insertar un nuevo catalogo: </strong></p> 
         <p align="center"> <strong>name:</strong> <input type="text" name="catalogoname"/> 
         <input name="aceptarButton" type="submit" value="Aceptar" /> </p>   
       </form>    
    </div> 
    <div id= "lista">
     <?php if(count($vars['catalogs'])==0) echo "No hay catalogos"; ?>
     <?php foreach($vars['catalogs'] as $catalogs) { ?>    
    </div>
 <div id="catalogo" class="UserPar">
          
           <div id="showCatalogo<?php echo $catalogs->id; ?>">
                <div class="divRight">
                    <p><strong>Id:</strong>  <?php echo $catalogs->id; ?>
                        <strong>name:</strong>: <?php echo $catalogs->name; ?><br />   
                    </p>
                </div>
           <ul id="menuItem">                   
                    <li><a href="#" class="editLink" name="Catalogo<?php echo $catalogs->id; ?>">editar</a></li>
                    <li><a class="deleteLink" href="crud.php?action=eliminarCatalogo&id=<?php echo $catalogs->id; ?>">eliminar</a></li>
           </ul>
             
          </div>  

           <div id="editCatalogo<?php echo $catalogs->id; ?>" class="UserPar" style="display:none;">
                           <form action="crud.php" method="post">
				           <input type="hidden" name="action" value="updateCatalogo"/>
                           <input type="hidden" name="id" value="<?php echo $catalogs->id; ?>" />	
                           <div class="divRight">
                            <p><strong>name:</strong>  <input name="name" type="text" value="<?php echo $catalogs->name; ?>" />                             
                            <br />                                                              
                              </p>
                	      </div>
                          <div id="menuButtons">
                            <input name="" type="submit" value="Guardar" />
                            <input name="Catalogo<?php echo $catalogs->id; ?>" type="button" value="Cancelar" class="showButton" />
                	     </div>
                   </form>            
             </div>  
                                                     <?php }?>                      

</div>
</div>                                                     