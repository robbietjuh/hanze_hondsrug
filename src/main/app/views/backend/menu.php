                <ul class="list-group backend-menu">
                    <a href="/backend"
                       class="list-group-item <?=($this->data['page'] == 'incidents') ? 'active' : '';?>">
                        <h3 class="list-group-item-heading">Incidenten</h3>
                        <p class="list-group-item-text">Beheer aangemelde incidenten</p>
                    </a>
                    <a href="/backend/questionnaires"
                       class="list-group-item <?=($this->data['page'] == 'questionnaires') ? 'active' : '';?>">
                        <h3 class="list-group-item-heading">Vragenlijsten</h3>
                        <p class="list-group-item-text">Beheer vragenlijsten en workarounds</p>
                    </a>
                    <a href="/backend/hardware"
                       class="list-group-item <?=($this->data['page'] == 'hardware') ? 'active' : '';?>">
                        <h3 class="list-group-item-heading">Configuratiemanagement</h3>
                        <p class="list-group-item-text">Beheer hardware en software</p>
                    </a>
                </ul>