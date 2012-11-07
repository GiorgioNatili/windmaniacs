 jQuery("#bannerdiv0").append("<button type='button' id='savebutton' name='savebanner' style='z-index: 999'>Save</button>");
	   
    /*	jQuery("#bannerdiv0").mousedown(function(e){
	  alert(e.which);
	});
   */
var str ="";
var insert = "";
var sArray;


jQuery("body").append("<div id='log'></div>")
jQuery("#log").css({ 'position':'fixed',
		     'top':'0px',
		    'left':'0px',
		    'width':'150px',
		    'height':'250px',
		    'z-index':'30',
		    'background-color':'#C3C3C3',
	    });

jQuery('div')
  .mouseover(function(e){
     e.stopPropagation();
     $target = jQuery(e.target);
     jQuery(this).css("border","1px solid red");
     jQuery(this).mouseout(function(){
	   jQuery(this).css("border","0px");
	});
   
  })
  .click(function(e){
	
	$bannerDiv = jQuery("#bannerdiv0").detach();
	
	e.stopPropagation();
	$target = jQuery(e.target);
	var click = $target.get(0).tagName;
	var divTree= "";
	/*var divTree2 = $target.parents().andSelf().map(function(){
					    return this.tagName + ":"  + jQuery(this).index();
					 }).get().reverse().join(' ');
	*/
	if($target.is("div"))
	{
	   var name = $target.get(0).tagName;
	   var idx = $target.index();
	   var id = $target.attr("id");
	   
	   divTree = $target.parents().andSelf().map(function(){
					    var obj= new Object;
					    obj.name = this.tagName;
					    obj.id   = jQuery(this).index();
					    return obj;
					 });
	   
	}else{
	   var $actDiv = $target.parents().map(function(){
			     if(this.tagName == "DIV"){
				return this;
			     }
			  
			  });
	   
	// jQuery("#log").html($actDiv.get().join(","));
	 divTree = $actDiv.parents().andSelf().map(function(){
					    var obj= new Object;
					    obj.name = this.tagName;
					    obj.id   = jQuery(this).index();
					    return obj;
					 });
	   var idx = $actDiv.index(this);
	  //var idx = $target.index($actDiv);
	   var id = $actDiv.attr("id");
	   var name = $actDiv.get(0).nodeName;
	   
	 //  jQuery(divTree).find("div").append("#bannerdiv0");
	
	 }
	 str = "länge: " + divTree.length + "<br>";
	 insert = "";
	 var tagName = "";
	// str += divTree[0].name + "<br>";
	 
	 if(divTree.length > 0)
	   {
	      
	      jArray=jQuery.map(divTree,function(elem){
			       el = '[{"' + elem.name.toLowerCase() + '":"' + elem.id + '"}]'
				//alert(el);
				return el;
			    });
	      sArray="[" + jArray.join(",") + "]";
	      alert( sArray );
	      /** json Test
	      ddivTree = jQuery.parseJSON(sArray);
	      **/
	      for (i=0;i < ddivTree.length;i++)
	      {
		str += divTree[i].name.toLowerCase() + ": " + divTree[i].id + "<br>";
		tagName = divTree[i].name.toLowerCase();
		
		insert += tagName;
		
		 if(tagName.substr(-4,4) != "body")   
		    insert += ":eq(" + divTree[i].id + ")";
		 
		 if( divTree.length-1 != i)
		    insert += " > ";
		    
	      }
	   }
	 
	 jQuery("#log").html(str + insert);
	
	// $bannerDiv.appendTo(insert);
	 jQuery(insert).append($bannerDiv);

	/* jQuery("#log").html("Klick Tag: " +click +"<br>"+name + " ID :"+id+" -> <b>"+ idx +"</b><br>Tree1: "
		    + divTree +"<br>");
	*/
   });
   
   
  
   jQuery("#savebutton").click(function(e){
	      e.stopPropagation();
	      
	      jQuery.get('<?php echo WP_BANNER_PATH .'/banner_adminsave.php'?>',{banner_tree:sArray});
	 });