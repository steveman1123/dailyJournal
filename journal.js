function getMonth(e) {
  links = e.parentNode.nextSibling;
  //populate the wrapper with links if it hasn't already been
  if(links.children.length==0) {
    //request contents of the folder
    var url = "./getlinks.php?y="+e.innerHTML;
    fetch(url).then(response => response.json())
      .then(json => {
        //create the new wrapper
        //add the new wrapper right after the selected element
        for(var i=0;i<json.length;i++) {
          //create the link and append it
          let m = document.createElement("p");
          let lw = document.createElement("div");
          lw.classList.add("linkwrapper");
          m.innerHTML = '<a href="javascript:;" onclick="getDate(this);">'+json[i]+'</a>';
          links.appendChild(m);
          links.appendChild(lw);
        }
      });
  }
  //toggle the links
  links.style.display = (links.style.display=='block' ? 'none' : 'block');
}

function getDate(e) {
  //populate the .linkwrapper and get the month/year
  var links = e.parentNode.nextSibling;
  var y = e.parentNode.parentNode.previousSibling.children[0].innerHTML;
  var m = e.innerHTML;
  //only populate if not already populated
  if(links.children.length==0) {
    var url = "./getlinks.php?"+new URLSearchParams({"y":y,"m":m});
    fetch(url).then(response => response.json())
    .then(json => {
      for(var i=0; i<json.length;i++) {
        let d = document.createElement("p");
        d.innerHTML = '<a href="?date='+json[i]+'">'+json[i]+'</a>';
        links.appendChild(d);
      }
    });
  }
  //toggle link list
  links.style.display = (links.style.display=='block' ? 'none' : 'block');
}
