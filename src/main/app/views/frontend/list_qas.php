            <ul class="list-group questionnaire-list">
                <?php foreach($this->data['questionnaires'] as $questionnaire) { ?>
                    <a href="/questionnaire/<?=$questionnaire['id'];?>" class="list-group-item">
                        <i class="glyphicon glyphicon-<?=$questionnaire['icoon'];?>"></i> <span><?=$questionnaire['titel'];?></span>
                    </a>
                <?php } ?>
            </ul>