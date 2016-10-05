<?php if ($msg) echo $msg; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <?php echo $nav_secundario; ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header"><?php echo $query['NomePaciente'] . ' <small>Prontuário: ' . $query['Prontuario'] . '</small>'; ?></h1>

            <div class="col-md-3 col-lg-3 " align="center"> 
                <img alt="User Pic" src="<?php echo base_url(); ?>arquivos/imagens/profile-m.jpg" 
                     class="img-circle img-responsive" width="75%">
            </div>
            <div class=" col-md-9 col-lg-9 "> 
                <table class="table table-user-information">
                    <tbody>

                        <tr>
                            <td class="col-md-4 col-lg-4"><span class="glyphicon glyphicon-user"></span> Identificador:</td>
                            <td><?php echo $query['idApp_Responsavel']; ?></td>
                        </tr>
                        <tr>
                            <td class="col-md-4 col-lg-4"><span class="glyphicon glyphicon-user"></span> Ficha N°:</td>
                            <td><?php #echo $query['RegistroFicha']; ?></td>
                        </tr>                    

                        <tr>
                            <td class="col-md-3 col-lg-3"><span class="glyphicon glyphicon-user"></span> Nome do Responsável:</td>
                            <td><?php echo $query['NomeResponsavel']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-gift"></span> Data de Nascimento:</td>
                            <td><?php echo $query['DataNascimento']; ?></td>
                        </tr>
                        <tr>
                            <td><span class="glyphicon glyphicon-phone-alt"></span> Telefone Principal:</td>
                            <td><?php echo $query['Telefone1']; ?></td>
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
        </div>
    </div>
</div>