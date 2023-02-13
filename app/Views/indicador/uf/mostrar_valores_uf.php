<?=$header?>

    <div class="container">
        
        <div class="col-lg-10">
            <h3>Datos historicos de UF</h3>
            <table class="table table-striped table-dark" id="tabla">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Código</th>
                        <th scope="col">Unidad</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($datos as $dato):?>
                            <tr>
                                <td><?php echo $dato['id']?></td>
                                <td><?php echo $dato['nombre']?></td>
                                <td><?php echo $dato['codigo']?></td>
                                <td><?php echo $dato['unidad']?></td>
                                <td><?php echo $dato['valor']?></td>
                                <td><?php echo $dato['fecha']?></td>
                                <td>
                                    <a href="<?= base_url('editar/' . $dato['id']); ?>" class="btn btn-secondary" type="button">Editar</a>
                                    <a href="<?= base_url('borrar/' . $dato['id']); ?>" class="btn btn-danger" type="button">Borrar</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        

                </tbody>
                <a href="<?= base_url('insertar'); ?>" class="btn btn-secondary" type="button">Nuevo Indicador</a>
            </table>
            
        </div>
    </div>

<?=$footer?>
<script>

    $(document).ready(function(){
        $('#tabla').DataTable({
            language : {
                url : 'https://cdn.datatables.net/plug-ins/1.13.2/i18n/es-ES.json'
            }
        });
    });
    
</script>

