<?php if ($msg) echo $msg; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <?php echo $nav_secundario; ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header"><?php echo $query['NomeResponsavel'] . ' <small>Identificador: ' . $query['idApp_Responsavel'] . '</small>'; ?></h1>

            <div class="col-md-2 col-lg-2 " align="center"> 
                <img alt="User Pic" src="<?php echo base_url() . 'arquivos/imagens/profile-' . $query['profile'] . '.png'; ?>" 
                     class="img-circle img-responsive">
            </div>
            <div class=" col-md-10 col-lg-10 "> 
                <table class="table table-user-information">
                    <tbody>

                        <tr>
                            <td class="col-md-3 col-lg-3"><span class="glyphicon glyphicon-user"></span> Ficha N°:</td>
                            <td><?php echo $query['RegistroFicha']; ?></td>
                        </tr>                    
                        <tr>
                            <td><span class="glyphicon glyphicon-gift"></span> Data de Nascimento:</td>
                            <td><?php echo $query['DataNascimento']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-phone-alt"></span> Telefone:</td>
                            <td><?php echo $query['Telefone']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-heart"></span> Sexo:</td>
                            <td><?php echo $query['Sexo']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-home"></span> Endereço:</td>
                            <td><?php echo $query['Endereco'] . ' ' . $query['Bairro'] . ' ' . $query['Municipio']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-envelope"></span> E-mail:</td>
                            <td><?php echo $query['Email']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-file"></span> Obs:</td>
                            <td><?php echo nl2br($query['Obs']); ?></td>
                        </tr>                        

                    </tbody>
                </table>
                    
            </div>
        
            <div class="row">
                
                <hr>

                <div class=" col-md-12 col-lg-12">
                    
                    <?php
                    if (!$list) {
                    ?>
                        <a class="btn btn-lg btn-warning" href="<?php echo base_url() ?>pet/cadastrar" role="button"> 
                            <span class="glyphicon glyphicon-plus"></span> Cadastrar Novo Pet
                        </a>
                        <br><br>
                        <div class="alert alert-info" role="alert"><b>Nenhum animal cadastrado</b></div>
                    <?php
                    } else {
                        echo $list;
                    }
                    ?>       
                    
                </div>
            </div>                                   
        </div>
    </div>       
</div>