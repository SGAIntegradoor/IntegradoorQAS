<head>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta2/css/all.css" integrity="sha384-OA4SkQ1hW5kfQF3/OBdzK99bg7sQKT6+yXxq5Iu7QvGrrkrBsX3p5SRy9CrJ0+Gx" crossorigin="anonymous">
</head>

<div class="content-wrapper">
    <section class="content-header">
        <h1 style="margin-bottom: 0%;">
        Cotizador Seguro de Salud
        </h1>
        <ol class="breadcrumb">
            <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li class="active">Salud</li>
        </ol>
    </section>

    <section class="content">
    <div class="box">
    <?php include_once './vistas/modulos/SaludCot/vistas/cards.php'; ?>
    <?php include_once './vistas/modulos/SaludCot/vistas/cotizadorSalud.php'; ?>
    </div>
    </section>
</div>
<link rel="stylesheet" href="vistas\modulos\SaludCot\css\cards.css">
<script src="vistas\modulos\SaludCot\js\responsiveCards.js?v=<?php echo (rand()); ?>"></script>
