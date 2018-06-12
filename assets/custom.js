//countryChanged();
//cityChanged();
// currencyChanged();
// fromPlaceCategoryChanged();
// toPlaceCategoryChanged();
//$(".country").change(countryChanged);
// $(".city").change(cityChanged);
// $(".currency").change(currencyChanged);
// $(".from_place_category").change(fromPlaceCategoryChanged);
// $(".to_place_category").change(toPlaceCategoryChanged);
initFilters();
$(".selectfilter").change(selectFilter);
// $(".selectfilter-container").change(selectFilterContainer).addClass("change-added");
// $(function(){
	$(".selectfilter").trigger('change');
// 	$(".selectfilter-container").trigger('change');
// });

function selectFilter(){
	if($(this).hasAttr("data-filter")){
		filter("select." + $(this).data('filter'), $(this).data('type'), $(this).find("option:selected").val());
	}
}

function filter(elm, criteria, cvalue){
	console.log(elm, criteria, cvalue);
	if(!exists(elm)) return false;
	value = $(elm + " option:selected").val();
	$(elm + " option").removeClass("hidden");
	$(elm + " option:not([data-" + criteria + "='" + cvalue + "'])").addClass("hidden");
	if($(elm + " option[value='" + value + "']").hasClass("hidden") || value == undefined){
		$(elm).val($(elm + " option:not('.hidden'):first").val());
		if($(elm + " option:selected").hasClass('hidden')){
			$(elm).val('');
			$(elm).parent().find('filter-option').text('');
		}
	}
	$(elm).trigger("change");
	$(elm).selectpicker('refresh');
	// console.log($(elm).hasAttr('data-filter'));
	// if($(elm).hasAttr('data-filter')){
	// 	filter($(elm).data("filter"), $(elm).data("filter-type"), $(elm + " option:not('.hidden'):first").val());
	// }
}

selectfiltercontianercounter = 0;

function initFilters(){	
	$(".selectfilter-container:not(.selectfilter-container-enabled)").change(selectFilterContainer).addClass("selectfilter-container-enabled");	
	//setTimeout(function(){
		$(".selectpicker").selectpicker();
		$(".selectfilter").trigger('change');
		$(".selectfilter-container").trigger('change');
	//}, 100);
}

function selectFilterContainer(){
	if(!$(this).hasClass("selectfilter-container-enabled")){
		cl = "selectfiltercontianercounter-" + selectfiltercontianercounter++;
		console.log(cl);
		$(this).addClass(cl);
	}

	if($(this).hasAttr("data-filter")){
		// filterContainer("." + $(this).data('filter'), $(this).data('wrapper'), $(this).data('type'), $(this).find("option:selected").val());
		// filterContainer($(this), $(this).data('wrapper'), $(this).data('type'), $(this).find("option:selected").val());
		filterContainer($(this), $(this).data('type'));
	}
}

function filterContainer(elm, criteria){
	cvalue = $(elm).find("option:selected").val();
	parent = elm;
	found = 0; 
	count = 1;
	do {
		parent = $(parent).parent();
		found = $(parent).find("." + $(elm).data("filter")).length;
		// console.log($(elm).data("filter"), "Found?", found);
		if(count++ == 10){
			break;
		}
    //console.log(wrapper);
	} while(found == 0);
	filterTarget = $(elm).data("filter");
	console.log(filterTarget);
	filterTargetObj = $(parent).find("." + filterTarget);
	// console.log($(parent).prop("tagName"), filterTarget, $(filterTargetObj).prop("tagName")); //TR source_city DIV
	// console.log(elm, filterTarget, filterTargetObj);

	if(filterTargetObj == undefined) return false;
	value = $(filterTargetObj).find("option:selected").val();
	$(filterTargetObj).find("option").removeClass("hidden");
	$(filterTargetObj).find("option:not([data-" + criteria + "='" + cvalue + "'])").addClass("hidden");
			console.log($(filterTargetObj).parent().find('.filter-option').text());
	if($(filterTargetObj).find("option[value='" + value + "']").hasClass("hidden") || value == undefined || value == ''){
		$(filterTargetObj).val($(filterTargetObj).find("option:not('.hidden'):first").val());
		if($(filterTargetObj).find("option:selected").hasClass('hidden')){
			console.log($(filterTargetObj).parent().find('.filter-option').text());
			$(filterTargetObj).val('');
			$(filterTargetObj).parent().find('.filter-option').text('');
		}
	}
	$(filterTargetObj).selectpicker('refresh');
	$(filterTargetObj).trigger("change");
}

function filterContainer3(elm, container, criteria, cvalue){
	container = container.toUpperCase();
	console.log($(elm).prop("tagName"), container, criteria, cvalue);
	e = $(elm).parent();
	wrapper = $(e).prop("tagName");

	while(wrapper != container){
		e = $(e).parent();
    wrapper = $(e).prop("tagName");
    console.log(wrapper);
	}
	obj = $(elm).data("filter");
	console.log(elm);
	console.log(obj);

	if(obj == undefined) return false;
	value = $(obj).find("option:selected").val();
	$(obj).find("option").removeClass("hidden");
	$(obj).find("option:not([data-" + criteria + "='" + cvalue + "'])").addClass("hidden");
	if($(obj).find("option[value='" + value + "']").hasClass("hidden") || value == undefined){
		$(obj).val($(obj).find("option:not('.hidden'):first").val());
		if($(obj).find("option:selected").hasClass('hidden')){
			$(obj).val('');
			$(obj).parent().find('filter-option').text('');
		}
	}
	// $(e).find(elm).trigger("change");
	// $(e).find(elm).selectpicker('refresh');
}

function filterContainer2(elm, container, criteria, cvalue){
	container = container.toUpperCase();
	console.log($(elm).prop("tagName"), container, criteria, cvalue);
	
	e = $(elm).parent();
	wrapper = $(e).prop("tagName");

	while(wrapper != container){
		e = $(e).parent();
    wrapper = $(e).prop("tagName");
    console.log(wrapper);
	}
	console.log(e);
	obj = $(e).find(elm);

	if(obj == undefined) return false;
	value = $(obj).find("option:selected").val();
	$(obj).find("option").removeClass("hidden");
	$(obj).find("option:not([data-" + criteria + "='" + cvalue + "'])").addClass("hidden");
	if($(obj).find("option[value='" + value + "']").hasClass("hidden") || value == undefined){
		$(obj).val($(obj).find("option:not('.hidden'):first").val());
		if($(obj).find("option:selected").hasClass('hidden')){
			$(obj).val('');
			$(obj).parent().find('filter-option').text('');
		}
	}
	$(e).find(elm).trigger("change");
	$(e).find(elm).selectpicker('refresh');
	// console.log($(elm).hasAttr('data-filter'));
	// if($(elm).hasAttr('data-filter')){
	// 	filter($(elm).data("filter"), $(elm).data("filter-type"), $(elm + " option:not('.hidden'):first").val());
	// }
}

function countryChanged() {
	//country = $(".country option:selected").val();
	country = $(this).find("option:selected").val();
	console.log("countryChanged", country);
	if($(this).hasAttr("data-city")){
		filter("." + $(this).data("city"), 'country', country);
	} else{
		filter(".city", 'country', country);
	}
	if($(this).hasAttr("data-filter")){
		filter("." + $(this).data("filter"), 'country', country);
	}
	filter(".state", 'country', country);
}

function cityChanged() {
	city = $(this).find("option:selected").val();
	console.log("1. cityChanged");
	if(city == undefined) return false;
	console.log("2. cityChanged");
	console.log(city);
	if(exists(".place") && !$(this).hasAttr("data-filter-place")){
		filterPlace(".place_category", ".city", ".place");
	}
	if($(this).hasAttr('data-filter')){
		filter($(this).data("filter"), 'city', city);
	}
	if($(this).hasAttr("data-filter-place")){
		if(city != ''){
			var filterTarget = $(this).attr("data-filter-place");
			if(filterTarget.includes("from")){
				filterPlace(".from_place_category", ".from_city", ".from_place");
			}
			if(filterTarget.includes("to")){
				filterPlace(".to_place_category", ".to_city", ".to_place");
			}
		}	
	}
}

function currencyChanged(){
	currency = $(".currency option:selected").text();
	$(".currency-view").text(currency);
}

function fromPlaceCategoryChanged(){
	filterPlace(".from_place_category", ".from_city", ".from_place");
}
function toPlaceCategoryChanged(){
	filterPlace(".to_place_category", ".to_city", ".to_place");
}

function filterPlace(place_category_elm, city_elm, place_elm){
	place_category = $(place_category_elm + " option:selected").val();
	place = $(place_elm + " option:selected").val();
	$(place_elm + " option").removeClass("hidden");
	$(place_elm + " option:not([data-place_category='" + place_category + "'])").addClass("hidden");
	$(place_elm + " option[value='']").removeClass("hidden");
	if(exists(city_elm)){
		city = $(city_elm + " option:selected").val();
		if(city != ''){
			$(place_elm + " option:not([data-city='" + city + "'])").addClass("hidden");	
		}		
	}
	$(place_elm + " option[value='']").removeClass("hidden");
	if($(place_elm + " option[value='" + place + "']").hasClass("hidden") || place == undefined){
		$(place_elm).val($(place_elm + " option:not('.hidden'):first").val());
	}
	$(place_elm).trigger("change");
}


//Storage
//console.log(ci('test'));

  function uif(){
    // value 
  }
  //store def if not stored
  function sif(key, def){
    value = ci(key);
    if(value){
      return true;
    } else{
      si(key, def);
      return false;
    }
  }
  //check exists or not
  function ci(key){
    value = gi(key);
    if(value){
      return true;
    }
    return value;
  }
  //retrive or return false localStorage
  function gi(key){
    value = sessionStorage.getItem(key);
    if(value == null){
      return false;
    }
    console.log("Retrived: ", key);
    return value;
  }
  //Store
  function si(key, value){
    console.log("Stored: ", key);
    sessionStorage.setItem(key, value);
  }
  function di(key){
    console.log("Removed: ", key);
    //sessionStorage.removeItemSilent(key);
    sessionStorage.removeItem(key);
  }

  ////
$(".attr-filter").keyup(function(){
	var selectSize = $(this).val();
  attrFilter(selectSize);
});
function attrFilter(e) {
  var regex = new RegExp('\\b' + e + '\\b');
  		$('.size').hide().filter(function () {
      return regex.test($(this).data('size'))
  }).show();
}

$(".attr-match").keyup(function(){
	var selectSize = $(this).val();
  attrMatch(selectSize);
});
function attrMatch(e) {
    var regex = new RegExp('\\b\\w*' + e + '\\w*\\b');
    		$('.size').hide().filter(function () {
        return regex.test($(this).data('size'))
    }).show();
}