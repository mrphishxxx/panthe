function flip_left(){
	 mag=document.all["flip"];
	 mark=document.all["mark"];
	 if (mark.style.display=="none")
	 {
	  mark.style.display="block";
	  mag.style.position="relative";
	  mag.style.right= -2000;
	  $("#flip").animate({right: 0}, 1500);
	 }
	 else
	 {
      mark.style.display="none";
	  mag.style.position="absolute";
	  mag.style.right = 0;
	 }
};

function flip_telefon(){
	 mag=document.all["telefon"];
	 mark=document.all["link_tel"];
	 if (mag.style.left=="-440px")
	 {
	  $("#telefon").animate({left: 0}, 1500);
	  mark.style.color ="white";
	 }
	 else
	 {
      $("#telefon").animate({left: -440}, 1500);
      mark.style.color ="#ed4a14";
	 }
};

function goTo(where) {
	document.location.replace(where);
	return false;
};

function flip_unik(){
	 mag=document.all["flip_unik"];
	 mark=document.all["link_unik"];
	 if (mark.style.left=="0px")
	 {
     mark.innerHTML="Восстановление плит";
     mark.style.left="10px";
     mag.innerHTML="<H3>Сварочные работы</H3><p>" +
     "Мы производим сварочные работы на объектах, там, где есть в этом необходимость. "+
     "Это усиление перил на балконах, усиление ветхих плит  с использованием "+
     "металлических углов 50х50, 60х60, снятие старых перил и установка новых, "+
     "вынос балконов для увеличения полезного пространства. По желанию наших "+
     "клиентов мы произведем любые сварочные работы.<br/>"+
	 "Установка крыш и козырьков на крайних этажах.<br/>"+
	 "Как правило, в «хрущевках» и «брежневках» балконные плиты ничем не защищены. "+
	 "Со временем они подвергаются воздействию осадков, ветра. Для того чтобы "+
	 "защитить балкон от воздействия природных явлений мы предлагаем Вам "+
	 "остекление с последующей установкой крыши.</br> "+
	 "Сама крыша изготавливается на нашем производстве по размеру "+
	 "устанавливаемого балкона. Также мы устанавливаем козырьки на крайних этажах "+
	 "без остекления.</p>";
	 }
	 else
	 {
	 mark.innerHTML="Сварочные работы";
     mark.style.left="0px";
     mag.innerHTML="<H3>Восстановление плит</H3>"+
	 "<p>"+
	 "Восстановление плит производится с применением  материалов, по своим характеристикам "+
	 "превосходящим железобетонные конструкции. Компания «Балкон Сервис» ПЕРВАЯ в Смоленске, "+
	 "использующая современные сухие смеси, изготавливающиеся с использованием нано-технологий немецкой фирмы BASF."
	 "</p> ";
	 }
};
