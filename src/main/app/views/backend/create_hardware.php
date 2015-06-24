<?=$this->renderView("header");?>

        <ol class="breadcrumb">
            <li><a href="/backend">Dashboard</a></li>
            <li><a href="/backend/hardware">Configuratiemanagement</a></li>
            <li class="active">Nieuw configuratie item aanmaken</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <?=$this->renderView("backend/menu");?>
            </div>
            <div class="col-md-8">
                <?php if(isset($this->data['error'])) { ?>

                    <div class="alert alert-danger">
                        <strong>Het configuratie-item kon niet aangemaakt worden.</strong>
                        <p><?=$this->data['error'];?></p>
                    </div>

                <?php } ?>

                <form action="" method="post">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="id">Identificatiecode</label>
                            <input type="text" name="id" id="id" class="form-control" required />
                        </div>

                        <div class="form-group col-md-6">
                            <label for="soort">Soort</label>
                            <input type="text" name="soort" id="soort" class="form-control" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="merk">Merk</label>
                            <input type="text" name="merk" id="merk" class="form-control" required />
                        </div>

                        <div class="form-group col-md-6">
                            <label for="leverancier">Leverancier</label>
                            <input type="text" name="leverancier" id="leverancier" class="form-control" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="locatie">Locatie</label>
                            <select name="locatie" id="locatie" class="form-control" required>
                                <?php foreach($this->data['locations'] as $location) { ?>
                                <option value="<?=$location['locatie'];?>"><?=ucfirst($location['locatie']);?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="aanschafjaar">Aanschafjaar</label>
                            <input type="text" name="aanschafjaar" id="aanschafjaar" class="form-control"
                                   value="<?=date('Y');?>" disabled required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="software">Software</label>
                        <select name="software[]" id="software" class="form-control" style="height: 140px" multiple>
                            <?php foreach($this->data['software'] as $software) { ?>
                            <option value="<?=$software['identificatiecode'];?>"><?=ucfirst($software['naam']);?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <hr />

                    <input type="submit" value="Item aanmaken &rarr;" class="btn btn-primary pull-right" />
                </form>
            </div>
        </div>

<?=$this->renderView("footer");?>