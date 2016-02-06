<?php
/* # this part isn't used yet.
#load login information to sql database
require('config.inc.php');

#connect to sql database
#$link=mysql_connect($database_host,$username,$password) or die(mysql_error());
#mysql_select_db($database,$link);mysql_set_charset('utf8');


$db = new PDO('mysql:host='.$database_host.';dbname='.$database.';charset=utf8', ''.$username.'', ''.$password.'');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$password='fs3SadhGFar4gd21';$username='sql';
*/

echo'<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
  <title>Create list of content in files</title>

  <script src="//code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.0.1/js.cookie.min.js"></script>

  <link href="ext/fancytree-master/src/skin-win7/ui.fancytree.css" rel="stylesheet" type="text/css">
  <script src="ext/fancytree-master/src/jquery.fancytree.js" type="text/javascript"></script>
  <script src="ext/fancytree-master/src/jquery.fancytree.persist.js" type="text/javascript"></script>
  <!-- (Irrelevant source removed.) -->

<style type="text/css">
</style>

<script type="text/javascript">
  var treeData = [';

#following php code is released under gpl
#copyright Kristoffer Bernssen

$checkfiletype=$_POST['filetype'];
if(empty($checkfiletype)){$checkfiletype='php';}
$matchdata=$_POST['tomatch'];
if(empty($matchdata)){$matchdata='mysql_query';}
error_reporting(0);$uniq=1;
$fileids=array();$dirids=array();$node=array();#$foldernode=array();
function filedirs($dirstart,$level,$theidparent) {
    $theidparent2=$theidparent;$level2=$level;$dirstart2=$dirstart;
    global $fileids,$dirids,$node,$uniq,$checkfiletype;#,$foldernode;
    $i=1;
    if($dirstart2!=='.'){$dirextra="$dirstart2/";}
    if($level2==0){$rootfolder=1;}
 if ($handle = opendir($dirstart2)) {
        ++$level2;
    $dirs=array();$diri=0;
    $files=array();$filei=0;
    while (false != ($entry = readdir($handle))) {
      if ($entry != "." && $entry != "..") {
          if(filetype($dirextra.$entry)=="dir"){
              $dirs[$diri]=$entry;++$diri;$thefile="";
          }elseif($entry!=='config.inc.php'){
            preg_match('/\.([^\.]*)$/i',$entry,$match);
            $thefile=$match[1];
            if($thefile==$checkfiletype){
                $files[$filei]=$dirextra.$entry;++$filei;$thefile="";
            }
         }
      }
    }
    $i2=0;
    $n=count($dirs);
    $nm=$n -1;
    $nf=count($files);
    $nfm=$nf -1;
    foreach($dirs as $dirpart){
        $diddir=1;
        if(!empty($dirpart)){
            $theid="id-$level2-$i-$uniq";++$uniq;
            if($dirstart2 != "."){$newdir=$dirstart2.'/'.$dirpart;}
            else{$newdir=$dirpart;}
            
            $dirids[$theid]=$newdir;
            if($i2!==0){echo",\n";}
            #echo str_repeat("\t",$level);
            echo "{folder: true, key:\"$theid\"";
            $i3=0;
            if ($handle2 = opendir($newdir)) {
                while (false != ($entry2 = readdir($handle2))) {
                    if ($entry2 != "." && $entry2 != "..") {
                        if (filetype($newdir.'/'.$entry2)=="dir"){
                            $found1=1;++$i3;
                        }else{
                          preg_match('/\.([^\.]*)$/i',$entry2,$match);
                          $thefile=$match[1];
                        }
                        if($thefile=='php'){$found1=1;++$i3;}
                    }
                }
            }
            if(isset($found1)){
               
                echo", title:\"$dirpart *$i3\", children: [\n";
                    filedirs($newdir,$level2,$theid);
                    echo"]}";unset($found1);$found2=1;
            }else{echo", title: \"$dirpart\"}";}
            ++$i;++$i2;
            }
        }
         
    
    if(isset($rootfolder)){
        $theid="id-$level2-$i-$uniq";++$uniq;++$i;$theidparent2=$theid;
        $dirids[$theid]='.';
        echo ",\n{folder: true,title: \"RootFolder\", key:\"$theid\"";
        if($nf>0){echo", children: [\n";$closeroot=1;}else{echo"}";}
    }elseif(($i2>0)&&($nf>0)&&isset($diddir)){echo",\n";}
    elseif(isset($found2)){unset($found2);echo",\n";}
    $i2=0;
    foreach($files as $filepart){
        if(!empty($filepart)){
        $theid="id-$level2-$i-$uniq";++$uniq;
        if(!empty($theidparent2)){
            if(!empty($node[$theidparent2])){$node[$theidparent2].=','.$theid;}
            else{$node[$theidparent2]=$theid;}
         }
        $fileids[$theid]=$filepart;
        preg_match('/([^\/]*)$/i',$filepart,$match);
        if($i2!==0){echo",\n";}
        echo "{title: \"$match[1]\", key:\"$theid\"}";
        ++$i;++$i2;
        }
    }if(isset($diddir)){unset($diddir);}
      if(isset($closeroot)){echo"]}";unset($closeroot);}
 }

    closedir($handle);
    
}
filedirs(".",0,"") ;
$rand=rand();
echo' ];
</script>';
if(isset($_POST['coff'])){$coff=1;}
elseif(isset($_POST['to'])){$con=1;}
if(!empty($coff.$con)){
    $nodesids=$_POST['ft_1'];$i=0;
    #echo "<pre>".print_r($nodesids)."</pre><br>";
     # $node[$theid]=$theid,$theid2.. $fileids[$theid] $dirids[$theid]
    
    $didarray=array();
     function checkiffolder($nodetmp) {
        global $i,$node,$dirids,$didarray;
        $thefilesidstmp=array();
        foreach($nodetmp as $theidtmp){
            if(!empty($dirids[$theidtmp])){
                $node_a=explode(',',$node[$theidtmp]);
                #echo $theidtmp.'<br>'.$node[$theidtmp].'<br>';
                if(empty($didarray[$theidtmp])){
                    $didarray[$theidtmp]=1;$tmp_a=checkiffolder($node_a);
                    if(!empty($tmp_a)){$thefilesidstmp=array_merge($thefilesidstmp,$tmp_a);}
                }
                
            }else{
                $thefilesidstmp[$i]=$theidtmp;++$i;
            }
        }
        return($thefilesidstmp);
     }
    $thefilesids=checkiffolder($nodesids);
    #echo "<pre>".print_r($node['id2.1'])."</pre><br>";
    $i3=0;
    foreach($thefilesids as $theid){

        $thefile=$fileids[$theid];
        if(!empty($thefile)){echo'<br>';
            $loadedfile=file_get_contents($thefile);
            $file_a=explode("\n",$loadedfile);
            $n=count($file_a);
            $matches="";$matches2="";
            error_reporting(0);
            $i2=1;$i=0;while ($i<$n) {
                $fileline=$file_a[$i];
                if(stristr($fileline,$matchdata)!==false){
                    ++$i3;
                    echo $thefile.' - Match: line '.$i2.': '.$fileline.'<br>';
                }
                ++$i;++$i2;
            }
        }
    }
}
unset($db);
echo"<br>$i3 Results found total in all files<p></p>";
# end of php code released under gpl
echo'<script type="text/javascript">
  $(function(){


    $("#tree3").fancytree({
    //      extensions: ["select"],
    extensions: ["persist"], 
    checkbox: true,
    persist: {
      // Available options with their default:
      cookieDelimiter: "~",    // character used to join key strings
      cookiePrefix: undefined, // \'fancytree-<treeId>-\' by default
      cookie: { // settings passed to jquery.cookie plugin
        raw: false,
        expires: "",
        path: "",
        domain: "",
        secure: false
       // expandLazy: true, // true: recursively expand and load lazy nodes
        //overrideSource: true,  // true: cookie takes precedence over `source` data attributes.
    },
    expandLazy: false, // true: recursively expand and load lazy nodes
    overrideSource: true,  // true: cookie takes precedence over `source` data attributes.
    store: "auto",     // \'cookie\': use cookie, \'local\': use localStore, \'session\': use sessionStore
    types: "active expanded focus selected"  // which status types to store
   },

      selectMode: 3,
      source: treeData,
      lazyLoad: function(event, ctx) {
        ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
      },
      loadChildren: function(event, ctx) {
        ctx.node.fixSelection3AfterClick();
      },
      select: function(event, data) {
        // Get a list of all selected nodes, and convert to a key array:
        var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
          return node.key;
        });
        $("#echoSelection3").text(selKeys.join(", "));

        // Get a list of all selected TOP nodes
        var selRootNodes = data.tree.getSelectedNodes(true);
        // ... and convert to a key array:
        var selRootKeys = $.map(selRootNodes, function(node){
          return node.key;
        });
        $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
        $("#echoSelectionRoots3").text(selRootNodes.join(", "));
      },
      dblclick: function(event, data) {
        data.node.toggleSelected();
      },
      keydown: function(event, data) {
        if( event.which === 32 ) {
          data.node.toggleSelected();
          return false;
        }
      },
      // The following options are only required, if we have more than one tree on one page:
    //  initId: "treeData",
      cookieId: "fancytree-Cb3",
      idPrefix: "fancytree-Cb3-"
    });

 $("form").submit(function() {
      // Render hidden <input> elements for active and selected nodes
      $("#tree3").fancytree("getTree").generateFormElements();
      //alert("POST data:\n" + jQuery.param($(this).serializeArray()));
      //return false; // return false to prevent submission of this sample
    });
});
  

</script>
</head>

<body class="example">
  <form action="" method="POST">
  
Filetype:<input type="text" value="'.$checkfiletype.'" name="filetype"><br>
Match data:<input type="text" value="'.$matchdata.'" name="tomatch">
<input type="submit" name="to" value="Read data">
<fieldset>
      <legend>Select 1</legend>
      <div id="tree3" name="selNodes">
      </div>
    </fieldset><br>
  </form>
  <div>Selected keys: <span id="echoSelection3">-</span></div>
  <div>Selected root keys: <span id="echoSelectionRootKeys3">-</span></div>
  <div>Selected root nodes: <span id="echoSelectionRoots3">-</span></div>
  <div>Active node: <span id="echoActive">-</span></div>
  <p class="description">
    This tree has <b>checkoxes and selectMode 3 (hierarchical multi-selection)</b> enabled.<br>
    A double-click handler selects the node.<br>
    A keydown handler selects on [space].
  </p>
</body>
</html>';
?>