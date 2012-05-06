            <div id="header" class="ui-widget-header">
                <div id="menu_wrapper">
                    <ul id="menu">
                    	<?php
                    	 if(in_array($_SESSION['user']['userType'],array('SuperAdmin','Admin'))): ?>
                        <li><?php echo anchor('/users', 'Usuarios') ?></li>
                        <li><?php echo anchor('/userTypes', 'Tipos de usuario') ?></li>
                        <?php endif; ?>
                        <li><?php echo anchor('/archivos', 'Archivos') ?></li>
                    </ul>
                </div>
                <div id="login_wrapper">
                	<?php
                	if (isset($_SESSION['user'])) {
                		echo $_SESSION['user']['name'];
                		echo anchor('/login/logout','Log Out',array('id'=>'login_button'));
                	}
                	else {
                		echo anchor('/login','Log In',array('id'=>'login_button'));
                	} ?>
                </div>
            </div>