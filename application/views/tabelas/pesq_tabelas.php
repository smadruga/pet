<?php if (isset($msg)) echo $msg; ?>

<div class="row">

    <div class="col-md-2"></div>
    <div class="col-md-8">

        <?php echo validation_errors(); ?>

        <div class="panel panel-primary">

            <div class="panel-heading"><strong><?php echo $titulo; ?></strong></div>
            <div class="panel-body">

                <p><b>Nome do Veterinário</b>: *</p>

                <div class="row">
                    <?php echo form_open($form_open_path, 'role="form"'); ?>
                    <div class="col-md-6">
                        <input type="text" class="form-control"
                               autofocus name="NomeVeterinario" value="<?php echo $query['NomeVeterinario'] ?>">
                    </div>

                    <div class="col-md-6">
                        <?php echo $button ?>
                    </div>
                    
                    <input type="hidden" name="idApp_Veterinario" value="<?php echo $query['idApp_Veterinario']; ?>">
                    </form>

                </div>

                <br>                
                
                <?php if (isset($list)) echo $list; ?>

            </div>

        </div>

    </div>
    <div class="col-md-2"></div>

</div>