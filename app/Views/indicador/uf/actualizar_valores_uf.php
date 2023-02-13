<?= $header ?>
<div class="container">
    <div class="card border-primary">
        <div class="card-body">
            <h4 class="card-title">Editar datos</h4>
            <p class="card-text">
            <form method="post" action="<?=site_url('/actualizar')?>" enctype="multipart/form-data">
            <input type="hidden" id="id" value="<?=$indicador['id']?>" name="id">
                <div class="form-group">
                    <label for="valor">Nombre</label>
                    <input id="valor" value="<?=$indicador['nombre']?>" class="form-control" type="text" name="nombre">
                </div>
                <div class="form-group">
                    <label for="valor">Código</label>
                    <input id="valor" value="<?=$indicador['codigo']?>" class="form-control" type="text" name="codigo">
                </div>
                <div class="form-group">
                    <label for="valor">Unidad</label>
                    <input id="valor" value="<?=$indicador['unidad']?>" class="form-control" type="text" name="unidad">
                </div>
                <div class="form-group">
                    <label for="valor">Valor</label>
                    <input id="valor" value="<?=$indicador['valor']?>" class="form-control" type="text" name="valor">
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input id="fecha" value="<?=$indicador['fecha']?>" class="form-control" type="text" name="fecha">
                </div>
                <br>
                <button class="btn btn-success" type="submit">Actualizar</button>
                <a href="<?=base_url('listar');?>" class="btn btn-info">Cancelar</a>
            </form>
            </p>
        </div>
    </div>
</div>
<?$footer?>
