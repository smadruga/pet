<?php if (isset($msg)) echo $msg; ?>

<div class="row">

    <div class="col-sm-3 col-md-2 sidebar">
        <?php echo $nav_secundario; ?>
    </div>

    <div class="col-sm-7 col-sm-offset-3 col-md-8 col-md-offset-2 main">

        <?php echo validation_errors(); ?>

        <div class="panel panel-<?php echo $panel; ?>">

            <div class="panel-heading"><strong><?php echo $titulo; ?></strong></div>
            <div class="panel-body">

                <?php echo form_open_multipart($form_open_path); ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="Data">Data: *</label>
                            <div class="input-group <?php echo $datepicker; ?>">
                                <input type="text" class="form-control Date" <?php echo $readonly; ?> maxlength="10" placeholder="DD/MM/AAAA"
                                       name="Data" value="<?php echo $query['Data']; ?>">
                                <span class="input-group-addon" disabled>
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-7 form-inline">
                            <div class="form-group">
                                <label for="Hora">Hora: *</label><br>
                                De 
                                <div class="input-group <?php echo $timepicker; ?>">
                                    <input type="text" class="form-control Time" <?php echo $readonly; ?> maxlength="5"  placeholder="HH:MM"
                                           accept=""name="HoraInicio" value="<?php echo $query['HoraInicio']; ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>

                                Até
                                <div class="input-group <?php echo $timepicker; ?>">
                                    <input type="text" class="form-control Time" <?php echo $readonly; ?> maxlength="5" placeholder="HH:MM"
                                           accept=""name="HoraFim" value="<?php echo $query['HoraFim']; ?>">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> 

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="idApp_Pet">Pet: *</label>
                            <a class="btn btn-xs btn-warning" href="<?php echo base_url() ?>pet/cadastrar" role="button"> 
                                <span class="glyphicon glyphicon-plus"></span> <b>Cadastrar Novo Pet</b> <span class="fa fa-paw"></span>
                            </a>
                            <select data-placeholder="Selecione uma opção..." class="form-control" <?php echo $readonly; ?>
                                    id="idApp_Pet" name="idApp_Pet">
                                <option value="">-- Selecione uma opção --</option>
                                <?php
                                foreach ($select['Pet'] as $key => $row) {
                                    if ($query['idApp_Pet'] == $key) {
                                        echo '<option value="' . $key . '" selected="selected">' . $row . '</option>';
                                    } else {
                                        echo '<option value="' . $key . '">' . $row . '</option>';
                                    }
                                }
                                ?>   
                            </select>          
                        </div>                                
                        <div class="col-md-4">
                            <label for="idApp_Veterinario">Veterinário: *</label>
                            <a class="btn btn-xs btn-warning" href="<?php echo base_url() ?>tabelas/cadastrar/veterinario" role="button"> 
                                <span class="glyphicon glyphicon-plus"></span> <b>Cadastrar Novo Veterinário</b>
                            </a>
                            <select data-placeholder="Selecione uma opção..." class="form-control" <?php echo $readonly; ?>
                                    id="idApp_Veterinario" name="idApp_Veterinario">
                                <option value="">-- Selecione uma opção --</option>
                                <?php
                                foreach ($select['Veterinario'] as $key => $row) {
                                    if ($query['idApp_Veterinario'] == $key) {
                                        echo '<option value="' . $key . '" selected="selected">' . $row . '</option>';
                                    } else {
                                        echo '<option value="' . $key . '">' . $row . '</option>';
                                    }
                                }
                                ?>   
                            </select>          
                        </div>                            
                    </div>
                </div>                 

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="Procedimento">Procedimento:</label>
                            <textarea class="form-control" id="Procedimento"
                                      name="Procedimento"><?php echo $query['Procedimento']; ?></textarea>
                        </div>
                    </div>
                </div>                 


                <?php if ($metodo == 2) { ?>


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 form-inline">
                                <label for="idTab_Status">Status:</label><br>
                                <div class="form-group">
                                    <div class="btn-group" data-toggle="buttons">
                                        <?php
                                        foreach ($select['Status'] as $key => $row) {
                                            if (!$query['idTab_Status'])
                                                $query['idTab_Status'] = 1;

                                            if ($query['idTab_Status'] == $key) {
                                                echo ''
                                                . '<label class="btn btn-' . $this->basico->tipo_status_cor($key) . ' active" name="radio" id="radio' . $key . '">'
                                                . '<input type="radio" name="idTab_Statust" id="radio" '
                                                    . 'autocomplete="off" value="' . $key . '" checked>' . $row
                                                . '</label>'
                                                ;
                                            } else {
                                                echo ''
                                                . '<label class="btn btn-default" name="radio" id="radio' . $key . '">'
                                                . '<input type="radio" name="idTab_Status" id="radio" class="idTab_Status" '
                                                    . 'autocomplete="off" value="' . $key . '" >' . $row
                                                . '</label>'
                                                ;
                                            }
                                        }
                                        ?>                                    
                                    </div>
                                </div>
                            </div>                           
                        </div>
                    </div>                 

                <?php } ?>                
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="Obs">Obs:</label>
                            <textarea class="form-control" id="Obs"
                                      name="Obs"><?php echo $query['Obs']; ?></textarea>
                        </div>
                    </div>
                </div>   

                <br>

                <div class="form-group">
                    <div class="row">
                        <input type="hidden" name="idApp_Consulta" value="<?php echo $query['idApp_Consulta']; ?>">
                        <input type="hidden" name="idApp_Agenda" value="<?php echo $_SESSION['log']['Agenda']; ?>">
                        <input type="hidden" name="idApp_Responsavel" value="<?php echo $query['idApp_Responsavel']; ?>">
                        <?php if ($metodo == 3) { ?>
                            <div class="col-md-12 text-center">                            
                                <button class="btn btn-lg btn-danger" id="inputDb" data-loading-text="Aguarde..." name="submit" value="1" type="submit">
                                    <span class="glyphicon glyphicon-trash"></span> Excluir
                                </button>
                                <button class="btn btn-lg btn-warning" id="inputDb" onClick="history.go(-1);
                                                return true;">
                                    <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
                                </button>
                            </div>                        
                        <?php } else { ?>
                            <div class="col-md-6">                            
                                <button class="btn btn-lg btn-primary" id="inputDb" data-loading-text="Aguarde..." type="submit">
                                    <span class="glyphicon glyphicon-save"></span> Salvar
                                </button>                            
                            </div>
                        <?php } ?>
                    </div>
                </div>                

                </form>

            </div>

        </div>

    </div>

</div>