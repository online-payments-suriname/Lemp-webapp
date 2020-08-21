function loadScript(url){
    script=document.createElement("script");
    script.src=url;
    document.head.appendChild(script);
}
loadScript("base/syncscroll.js");
loadScript("pdfjs/build/pdf.js");
(function ($) {
    //custom chainable jQuery functions
    //save the original init function so we can wrap it
    $.fn._init = $.fn.init
    //wrap query init to save the selector if it was proviced as a string
    $.fn.init = function( selector, context, root ) {
        return (typeof selector === 'string') ? new $.fn._init(selector, context, root).data('selector', selector) : new $.fn._init( selector, context, root );
    };
    //get the selector passed to jquery
    $.fn.getSelector = function() {
        return $(this).data('selector');
    };
    $.fn.splitAttr = function (attribute, i) {
        return $(this).attr(attribute).split(' ')[i];
    }
    //custom unchainable jQuery functions
    $.fn.ajaxInput = function (target, ifunction){
        $(this).click(function (e){
            type=$(this).attr('type');
            if(type!=="reset")
                e.preventDefault();
            tvalue=$(this).attr(target);
            ifunction(tvalue, this);
        });
    }
    $.fn.clickVisible = function (){
        if((this).is(':visible'))
            $(this).click();
    }
    $.fn.iObserve = function (ofunction, options){
        const observer = new IntersectionObserver(function(entries, observer){
            entries.forEach(entry => {
                ofunction(entry);
            });
        }, options);
        const target = document.querySelector($(this).getSelector());
        observer.observe(target);
    }
    $.fn.disableInput = function (dclass){
        $(this).attr('disabled','true');
        $(this).addClass(dclass);
    }
    $.fn.reEnableInputs = function (){
        $(this).prop("disabled",false);
        iclass=$(this).getSelector();
        if(iclass!==undefined)
            $(this).removeClass(iclass.substring(1));
    }
    $.fn.errorMessage = function (message){
        $(this).html('<div class="alert alert-danger">'+message+'</div>');
    }
    $.fn.debounceKeyup = function (kfunction){
        $(this).keyup(debounce(kfunction,300));
    }
}($));

function dateRangeBoundary(start, finish){
    legalStart=$(start).attr('min');
    $(finish).attr('min',legalStart);
    $('.date').attr('max',today());
}

function setColumnAttributes(column, key, value, novalue){
    if(novalue===undefined)
        $("."+column).attr(key,value);
    else
        $("."+column).removeAttr(key);
}

function today(){
    var now = new Date();
    const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: '2-digit', day: '2-digit' });
    const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(today );
    var today=`${year }-${month}-${day}`;
    return today;
}


function formData(formid, data, ajaxDone){
    formdata=$(formid).serializeArray()
    console.log('serializing form...');
    $(formdata).each(function(i,field){
        data[field.name] = field.value;
    });
    action=$(formid).attr('action');
    if(action === undefined){
        console.log('missing form attribute action');
        return;
    }
    loadData(data, action, ajaxDone);
}

function loadData(data, ajaxurl, ajaxDone){
    spinner='<div class="spinner-border"></div><div>'+data['loadtxt']+'</div>';
    console.log('loading '+data['action']+' data...');
    $(data['responsediv']).html(spinner);
    $.post(ajaxurl, data).fail(function(jqXHR, status, error){
        console.log(status+":"+error);
        console.log(jqXHR);
        $(data['responsediv']).errorMessage(status+":"+error);
    }).done(function(html, status){
        $(data['responsediv']).html(html);
        syncscroll.reset();
        if(ajaxDone!==undefined){
            ajaxDone();
            if(data['order']!==undefined)sortorder(data);
        }
    });

}

function sortorder(data){
    sort=(data['order']=='desc')?'asc':'desc';
    $('.'+data['linkId']).attr('href',sort);
}

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

$(document).ready(function(){
    $('.date').focus(function(){this.type='date'});
    $('.date').blur(function(){this.type='text'});
});
