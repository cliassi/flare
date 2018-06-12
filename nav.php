<!-- Navigation -->
<style type="text/css">

</style>
<?php
    $privileges = R::find("sys_privilege", "id>0");
?>
<nav class="navbar navbar-default navbar-static-top dp" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php print makeUri("home"); ?>"><img src='<?php print $appurl; ?>/assets/logo.png' width='32px' /></a>
        <a class="navbar-brand" href="<?php print makeUri("home"); ?>"><?php print APPTITLE." v".APPVERSION; ?></a>
    </div>
    <!-- /.navbar-header -->
    <ul class="nav navbar-top-links navbar-right">
        <li class='pointer animated infinite pulse'>
            <a class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-cloud-upload fa-fw"></i> Advertise
            </a>
        </li>
        <li class='dropdown'>
            <a class="dropdown-toggle" data-toggle="dropdown" href="public/support">
                <i class="fa fa-support fa-fw"></i> Support
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="http://www.sslbd.com/">
                        Systems & Services Limited<br>
                        House#40, Road 7/B, Block#H, Banani, Dhaka 1213<br>
                        Mobile: +880173457765<br>
                        E-mail : faiyazur.rahman@ssl.com.bd
                    </a>
                </li>
            </ul>
        </li>
        <li class='dropdown'>
            <a class="dropdown-toggle" data-toggle="dropdown" href="public/contactus">
                <i class="fa fa-headphones fa-fw"></i> Contact Us
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="http://www.sslbd.com/">
                        Systems & Services Limited<br>
                        House#40, Road 7/B, Block#H, Banani, Dhaka 1213<br>
                        Mobile: +880173457765<br>
                        E-mail : faiyazur.rahman@ssl.com.bd
                    </a>
                </li>
            </ul>
        </li>
        <li class='pointer'>
            <a class="dropdown-toggle" data-toggle="dropdown" onclick="window.print()">
                <i class="fa fa-print fa-fw"></i>
            </a>
        </li>
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="javascript:goto('<?php print makeUri("user", "user/profile_own"); ?>')"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li>
                <li class="divider"></li>
                <li><a href="javascript:goto('<?php print makeUri("login", "logout"); ?>')"><i class="fa fa-sign-out fa-fw"></i> Logout (<?php print username(); ?>)</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar dp" role="navigation">
        <div align="center" id='loader'><br ><img src="<?php print $appurl; ?>/framework/common/loader.gif" align="center" /></div>
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu" style="display:none">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" id="key" placeholder="Search..." onfocus="search()" onkeyup="search()">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <li class="active">
                    <a href="<?php print makeUri("home"); ?>" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <?php //var_dump($config);
                $menu_file = "temps/menus/menu.".APP.".".uid();
                if(file_exists($menu_file)){
                    include $menu_file;
                } else{
                    $menu_content = "";
                    $root_menu = select("*", "sys_privilege", "root=0 AND hidden=0 AND id<>84", "ORDER BY position");
                    //$permissions = permissions();
                    while ($root = mysqli_fetch_object($root_menu)) {
                        if(uid()==1){
                            $menus = select("*", "sys_privilege", "root=$root->id AND hidden=0", "ORDER BY  `position`");
                        } else{
                            //console_log("$permissions AND root=$root->id AND hidden=0 AND user=".uid()." ORDER BY  `position`");
                            $menus = select("SELECT * FROM sys_permission WHERE root=$root->id AND hidden=0 AND user=".uid()." ORDER BY  `position`");
                        }
                        if($menus->num_rows){
                            $menu_content .= "<li><a href='#'><i class='$root->glyphicon fa-fw'></i>".ucfirst($root->title)."<span class='fa arrow'></span></a>
                                <ul class='nav nav-second-level w100p'>";
                            while ($menu = mysqli_fetch_object($menus)) {
                                if(uid()==1){
                                    $menus3 = select("*", "sys_privilege", "root=$menu->id AND hidden=0", "ORDER BY  `position`");
                                } else{
                                    //console_log("$permissions AND root=$menu->id AND hidden=0 AND user=".uid()." ORDER BY  `position`");
                                    $menus3 = select("SELECT * FROM sys_permission WHERE root=$menu->id AND hidden=0 AND user=".uid()." ORDER BY  `position`");
                                }
                                $menu_content .= "<li><a href='".APPURL.(nn($menu->module)?"/$menu->module":"")."/$menu->link/$menu->option'><i class='$menu->glyphicon'></i> $menu->title".($menus3->num_rows?"<span class='fa arrow'></span>":"")."</a>";
                                if($menus3->num_rows){
                                    $menu_content .= "<ul class='nav nav-third-level'>";
                                        while ($menu3 = mysqli_fetch_object($menus3)) {
                                            $menu_content .= "<li><a href='".APPURL.(nn($menu3->module)?"/$menu3->module":"")."/$menu3->link/$menu3->option'><i class='$root->glyphicon fa-fw'></i>$menu3->title</a></li>";
                                        }
                                    $menu_content .= "</ul>";
                                }
                                $menu_content .= "</li>";
                            }
                            $menu_content .= "</ul>
                                </li>";
                        }
                    }
                    /*$acls = "SELECT acls_a.*, acls_p.link FROM (sys_acl acls_a JOIN sys_privilege acls_p) WHERE ((acls_a.privilege = acls_p.id) AND (acls_a.utype = 'u'))UNION SELECT acls_a.*, acls_p.link FROM ((sys_acl acls_a JOIN sys_privilege acls_p) JOIN sys_user_role acls_r) WHERE ((acls_a.privilege = acls_p.id) AND (acls_r.ur_role_id = acls_a.appliesto) AND (acls_a.utype = 'r'))";
                    $privileges = "SELECT pr_g.id gid, pr_g.title gtitle, pr_p.id pid, pr_p.position `position`, pr_p.title ptitle, pr_p.icon icon FROM (sys_privilege pr_g LEFT JOIN sys_privilege pr_p ON ((pr_g.id = pr_p.root))) WHERE ((pr_g.root = 0) AND (pr_g.active = 1) AND (pr_p.active = 1)) ORDER BY pr_g.position,pr_g.title,pr_p.link,pr_p.position";
                    $permission = "SELECT pe_p.id pid, pe_a.privilege privilege, pe_p.link link, pe_p.icon icon, pe_p.title title, pe_p.position position, pe_p.option `option`, pe_p.root root, pe_p.active active, pe_p.hidden hidden, pe_a.access access, pe_a.appliesto user, pe_p.controller controller, pe_p.show_in_frontpage show_in_frontpage FROM ($acls) pe_a JOIN sys_privilege pe_p WHERE (pe_a.privilege = pe_p.id)";
                    */
                    if(uid()==1){
                        $menus = select("*", "sys_privilege", "root=84 AND hidden=0", "ORDER BY  `position`");
                    } else{
                        //console_log("$permissions AND root=84 AND hidden=0 AND user=".uid()." ORDER BY  `position`");
                        $menus = select("SELECT * FROM sys_permission WHERE root=84 AND hidden=0 AND user=".uid()." ORDER BY  `position`");
                    }
                    while ($menu = mysqli_fetch_object($menus)) {
                        $menu_content .= "<li><a href='$menu->link'><i class='$menu->glyphicon'></i>".ucfirst($menu->title)."</a></li>";
                    }
                    print $menu_content;
                    //file_put_contents($menu_file, $menu_content);
                }
                ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
<script type="text/javascript">
    $(function(){
        $(".dropdown a").removeClass("active");
        $("#side-menu").show();
        $("#loader").hide();
        $(".dropdown").hover(function(){
            $(".dropdown").removeClass("open");
            $(this).addClass("open");
        });
        $(".navbar-static-top").mouseleave(function(){
            $(".dropdown").removeClass("open");
        });
    });
    function goto(uri){
        redir(uri);
    }

    function search(){
        var key = $("#key").val();
        cookie('key', key);
        if(key.length!=null){
            if(key.length>2){
                $.post("<?php print $appurl; ?>/framework/common/ajax/search.php", {'app':'<?php print APP; ?>',key:key}, function(data){
                    console.log(data);
                    if(data!=""){
                        $("#main-container").hide();
                        $("#search-result").html(data);
                        $("#search-result").show();
                    } else{
                        $("#main-container").show();
                        $("#search-result").hide();
                    }
                });
            } else{
                $("#main-container").show();
                $("#search-result").hide();
            }
        }
    }

    /*function search(){
        var key = $("#key").val();

        //var type = $("#stype").val();
        if(typeof(key)!='undefined'){
        if(!exists("#main-container")){
            var content = $("#main-container")();
            $("#main-container")("<div id='result'></div><div id='main-container'>" + content + "</div>");
        }
        cookie('key', key);
        if(key.length!=null)
        if(key.length>2){
            $.post("<?php print BASEURL; ?>/common/ajax/search.php", {'app':'<?php print APP; ?>',key:key}, function(data){
                if(data!=""){
                    $("#main-container").css("display","none");
                } else{
                    $("#main-container").css("display","block");
                }
                $("#main-container")(data);
            });
        } else{
            $("#result")("");
            $("#main-container").css("display","block");
        }
        }
    }*/
</script>