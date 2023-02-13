<?=$header?>
<div class="container">
    <h3>Gráfico Indicador Económico </h3>
    <div class="row justify-content-start">
        
        <div class="col-4">
            <div class="row">
                <div class="col-4">
                    <p>Cantidad de datos</p>
                </div>
                <div class="col">
                    <select name="select" id="select">
                        <option value="" disabled selected>Seleccione..</option>
                        <option value="AB">5</option>
                        <option value="AB">10</option>
                        <option value="AB">15</option>
                        <option value="AB">20</option>
                        <option value="AB">50</option>
                        <option value="AB">100</option>
                    </select>
                </div>
            </div>
           
        </div>
        <div class="col-8">
           <div class="row">
                <div class="col-1">
                    <p>desde</p>
                </div>
                <div class="col">
                    <input type="date" name="desde" id="desde">
                </div>
                <div class="col-1">
                    <p>hasta</p>
                </div>
                <div class="col">
                    <input type="date" name="hasta" id="hasta">
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-secondary" onclick="filtro()">Filtrar </button>
                    
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-danger" onclick="limpiarFiltros()">Limpiar</button>
                </div>

            </div>
        </div>
    </div>
    <div>
        <canvas id="grafico" width="400" height="300"></canvas>
    </div>
    <canvas id="grafico" width="400" height="300"></canvas>
</div>
<?=$footer?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var select = document.getElementById('select');
    var cantidad = 0
    let chart
    select.addEventListener('change',
        function(){
            var cantidad = this.options[select.selectedIndex].text;
            obtenerCantidad(cantidad)
        }
        
    )
    function obtenerCantidad(numero = 0)
    {
        cantidad = numero
    }

    $.ajax({
        type:"GET",
        url: `load`,
        dataType: "json",
        success : function(data){
            let fecha = new Array()
            let valor = new Array()
            $.each(data, function(i,item){
                fecha.push(item.fecha)
                valor.push(item.valor)
            })
            var grafico = document.getElementById('grafico')
            if (chart) {
                chart.destroy();
            }
            chart = new Chart (grafico,{
                type: 'bar',
                data :{
                    labels: fecha,
                    datasets:[
                        {
                            label : 'Valor del indicador',
                            data: valor,
                            backgroundColor:['#008000'],
                            borderColor:['black'],
                            borderWidth: 1
                        }
                    ]
                },
                options : {
                    scales : {
                        y:{
                            beginAtZero : true
                        }
                    }
                }
            })
        }
    })
    function limpiarFiltros()
    {
        document.getElementById('desde').value = null
        document.getElementById('hasta').value = null
        document.getElementById('select').value = ""
    }
    function filtro()
    {
        var desde = document.getElementById('desde').value
        var hasta = document.getElementById('hasta').value
        if(desde == "" || desde == null)
        {
            desde = "null"
        }
        if(hasta == "")
        {
            hasta  = "null"
        }
        $.ajax({
            url: `filtro/${cantidad}/${desde}/${hasta}`,
            type:"GET",
            dataType: "json",
            processData : false,
            contentType : false,
            error : function(jqXHR,exception){
                console.log(jqXHR)
                
            },
            success: function(data){
                console.log(data)
                let fecha = new Array()
                let valor = new Array()
                $.each(data, function(i,item){
                    fecha.push(item.fecha)
                    valor.push(item.valor)
                })
                var grafico = document.getElementById('grafico')
                if (chart) {
                    chart.destroy();
                }
                chart = new Chart (grafico,{
                    type: 'bar',
                    data :{
                        labels: fecha,
                        datasets:[
                            {
                                label : 'Valor del indicador',
                                data: valor,
                                backgroundColor:['#008000'],
                                borderColor:['black'],
                                borderWidth: 1
                            }
                        ]
                    },
                    options : {
                        scales : {
                            y:{
                                beginAtZero : true
                            }
                        }
                    }
                })
            } 
        })
    }
</script>