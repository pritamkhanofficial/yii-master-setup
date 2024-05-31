<?php
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use  yii\helpers\Url;
use  yii\widgets\Menu;
?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <!-- <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" key="t-menu">Menu</li>
            <li>
                <a href="apps-filemanager.html" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-dashboard">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-dashboard">Role</span>
                </a>
            </li>
        </ul> -->

        <?php echo  Menu::widget([
                'options' => ['class' =>'metismenu list-unstyled', 'id' =>'side-menu'],
                'items' => [
                    /* [
                        'label' => 'Menu',
                        'url' => ['#'],
                        'options' => ['class'=>'menu-title','key'=>'t-menu'], // for li class
                        'linkOptions' => false, // for  a tag class
                        'linkTemplate' => false,
                        
                    ], */
                    [
                        'label' => '<i class="bx bx-home-circle"></i><span key="t-dashboard">Dashboard</span>',
                        // 'options'=>['class'=>'list-group-item'],
                        'encode' => false,
                        'url' => ['dashboard/index'],
                        'template' => '<a href="{url}" class="waves-effect">{label}</a>',
                        //'linkOptions' => ['class'=>'waves-effect'],
                        'linkOptions'=>['class'=>'item-a-class'],
                        //'visible' => false // for auth
                    ],
                    /* [
                        'label' => '<i class="bx bx bx-wrench"></i><span key="t-master"> Master </span>',
                        'linkOptions'=>['class'=>'item-a-class'],
                        'template' => '<a href="javascript: void(0);" class="has-arrow waves-effect">{label}</a>',
                        'encode' => false,
                        'linkOptions' => ['class'=>'has-arrow waves-effect'],
                        'items' => [
                            ['label' => 'Section', 'url' => ['sections/index'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Class Type', 'url' => ['classtype/index'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Class', 'url' => ['schoolclass/index'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Subject', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Category', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Department', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Designation', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                           
                        ],
                        //'visible' => false // for auth
                    ], */
                    [
                        'label' => '<i class="fas fa-users"></i><span key="t-master"> Users </span>',
                        'linkOptions'=>['class'=>'item-a-class'],
                        'template' => '<a href="javascript: void(0);" class="has-arrow waves-effect">{label}</a>',
                        'encode' => false,
                        'linkOptions' => ['class'=>'has-arrow waves-effect'],
                        'items' => [
                            ['label' => 'Users List', 'url' => ['users/index'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Add User', 'url' => ['users/create'], 'linkOptions'=>['class'=>'item-a-class']],
                           
                        ],
                        //'visible' => false // for auth
                    ],
                    /* [
                        'label' => '<i class="fas fa-graduation-cap"></i><span key="t-master">Student Details</span>',
                        'linkOptions'=>['class'=>'item-a-class'],
                        'template' => '<a href="javascript: void(0);" class="has-arrow waves-effect">{label}</a>',
                        'encode' => false,
                        'linkOptions' => ['class'=>'has-arrow waves-effect'],
                        'items' => [
                            ['label' => 'Admission', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Student List', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Parent List', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Add Parent', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                           
                        ],
                        //'visible' => false // for auth
                    ], */

                    [
                        'label' => '<i class="fas fa-cogs"></i><span key="t-master"> Settings  </span>',
                        'linkOptions'=>['class'=>'item-a-class'],
                        'template' => '<a href="javascript: void(0);" class="has-arrow waves-effect">{label}</a>',
                        'encode' => false,
                        'linkOptions' => ['class'=>'has-arrow waves-effect'],
                        'items' => [
                            ['label' => 'Global Setting', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Role Permission', 'url' => ['roles/index'], 'linkOptions'=>['class'=>'item-a-class']],
                            ['label' => 'Database Backup', 'url' => ['#'], 'linkOptions'=>['class'=>'item-a-class']],
                           
                        ],
                        //'visible' => false // for auth
                    ],
                    /* [
                        'label' => '<i class="bx bx bx-wrench"></i><span key="t-master">Master</span>',
                        'linkOptions'=>['class'=>'item-a-class'],
                        'template' => '<a href="javascript: void(0);" class="has-arrow waves-effect">{label}</a>',
                        'encode' => false,
                        'linkOptions' => ['class'=>'has-arrow waves-effect'],
                        'items' => [
                            ['label' => 'Role', 'url' => ['roles/index'], 'linkOptions'=>['class'=>'item-a-class']],
                           
                        ],
                        //'visible' => false // for auth
                    ], */
                    [
                        'label' => '<i class="bx bx-home-circle"></i><span key="t-dashboard">User</span>',
                        'encode' => false,
                        'url' => ['users/index'],
                        'linkOptions' => ['class'=>'waves-effect'],
                        'visible' => false // for auth
                    ],
                    /* [
                        'label' => '<i class="bx bxs-dashboard"></i><span key="t-dashboard">Role</span>',
                        'encode' => false,
                        'url' => ['roles/index'],
                        'linkOptions' => ['class'=>'waves-effect'],
                        //'visible' => false // for auth
                    ], */
                    [
                        'label' => '<i class="mdi mdi-logout text-muted  align-middle me-1"></i><span key="t-logout">Logout</span>',
                        'encode' => false,
                        'url' => ['site/logout'],
                        'linkOptions' => ['class'=>'waves-effect'],
                        //'visible' => false // for auth
                    ]
                ],

                'submenuTemplate' => "\n<ul class='sub-menu' aria-expanded='false'>\n{items}\n</ul>\n",
                'encodeLabels' => false, //allows you to use html in labels
                'activateParents' => true,
                //'linkTemplate' => '<a href="{url}" class="waves-effect"><span>{label}</span></a>',
            ]); ?>
    </div>
    <!-- Sidebar -->
</div>
</div>
<!-- Left Sidebar End -->