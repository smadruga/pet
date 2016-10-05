<?php (isset($pet)) ? $query = $pet : FALSE; ?>

<div class="row">
    <div class="btn-group" role="group">
        <a class="btn btn-lg btn-primary active"> 
            <span class="glyphicon glyphicon-sort-by-attributes"></span> <?php echo '<b>Total de Pets:</b> ' . $query->num_rows() ?>
        </a>        
    </div>

    <div class="btn-group" role="group">
        <a class="btn btn-lg btn-warning" href="<?php echo base_url() ?>pet/cadastrar" role="button"> 
            <span class="glyphicon glyphicon-plus"></span> Cadastrar Novo Pet
        </a>
    </div>
</div>        

<br>

<?php
foreach ($query->result_array() as $row) {

    if ($row['StatusVida'] == 'O') {
        $row['class'] = 'danger';
        $row['icon'] = 'glyphicon glyphicon-info-sign';
        $row['vida'] = '<span class="label label-danger" style="font-size: 14px;">Óbito</span>';
    } else {
        $row['class'] = 'info';
        $row['icon'] = 'fa fa-paw';
        $row['vida'] = '';
    }
    ?>

        <div class="bs-callout bs-callout-<?php echo $row['class']; ?>" id=callout-overview-not-both> 

            <a class="btn btn-<?php echo $row['class']; ?>" href="<?php echo base_url() . 'pet/alterar/' . $row['idApp_Pet'] ?>" role="button"> 
                <span class="glyphicon glyphicon-edit"></span> Editar Dados
            </a>          
            <a class="btn btn-<?php echo $row['class']; ?>" href="<?php echo base_url() . 'pet/alterar/' . $row['idApp_Pet'] ?>" role="button"> 
                <span class="glyphicon glyphicon-time"></span> Marcar Consulta
            </a>            
            <a class="btn btn-<?php echo $row['class']; ?>" href="<?php echo base_url() . 'pet/alterar/' . $row['idApp_Pet'] ?>" role="button"> 
                <span class="glyphicon glyphicon-list"></span> Listar Consultas
            </a>  
            
            <br><br>
            
            <h4>
                <span class="<?php echo $row['icon']; ?>"></span> 
                Nome Pet: <?php echo $row['NomePet'] . ' <code><small>Identificador: ' . $row['idApp_Pet'] . '</small></code>'; ?>
                <?php echo $row['vida']; ?>
            </h4> 

            
            
            <p>
                <span class="glyphicon glyphicon-gift"></span> <b>Data de Nascimento:</b> <?php echo $row['DataNascimento']; ?> -
                <span class="glyphicon glyphicon-heart"></span> <b>Sexo:</b> <?php echo $row['Sexo']; ?>
            </p>

            <p>
                <span class="fa fa-paw"></span> 
                <b>Espécie:</b> <?php echo $row['Especie']; ?> -
                <b>Raça:</b> <?php echo $row['Raca']; ?>
            </p>

            <p>
                <span class="fa fa-paw"></span> 
                <b>Pelo:</b> <?php echo $row['Pelo']; ?> -
                <b>Cor:</b> <?php echo $row['Cor']; ?>
            </p>

            <p>
                <span class="glyphicon glyphicon-pencil"></span> <b>Obs:</b> <?php echo nl2br($row['Obs']); ?>
            </p>

        </div>        

<?php } ?>