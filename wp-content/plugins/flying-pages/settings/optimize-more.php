<?php
function flying_pages_optimize_more() {
?>
<style>
.grid {
   display: flex;
     flex-wrap: wrap;
}
.box {
   width: 300px;
   margin: 10px;
   background: white;
   border-radius: 7px;
   overflow: hidden;
   box-shadow: 2px 5px 5px 5px #ececec;
}
.box .info {
   padding: 0px 15px 15px 15px;
}
</style>

<div id="optimize-more">
</div>


<script>
fetch('https://optimize-more.wpspeedmatters.com/products.json')
  .then(response => {
    return response.json()
  })
  .then(sections => {
      let html = "";
      sections.forEach(section => {
         html += `<h3>${section.title}</h3>`;
         html += `<div class="grid">`;
         section.products.forEach(product => {
            html += `<div class="box">
                        <img src="${product.picture}" width="300px" height="150px"/>
                        <div class="info">
                           <h3>${product.name}</h3>
                           <p>${product.description}</p>
                           <a href="${product.buttonLink}" target="_blank"><button class="button button-primary">${product.buttonText}</button></a>
                        </div>
                     </div>`
         })
         html += `</div>`;
      })
      document.getElementById("optimize-more").innerHTML = html;
  })
  .catch(err => {
     console.log(err)
    document.getElementById("optimize-more").innerText = "Error!";
  })
</script>

<?php
}