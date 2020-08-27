function getScript(url){
    script=document.createElement("script");
    script.src=url;
    return script
}
function appendHead(url, callback){
    script=getScript(url);
    script.onreadystatechange = callback;
    script.onload=callback;
    document.head.appendChild(script);
}

function appendBody(url){
    script=getScript(url);
    document.body.appendChild(script);
}

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

    $.fn.dateRangeBoundary = function (start, finish){
        legalStart=$(start).attr('min');
        $(finish).attr('min',legalStart);
        $(this).attr('max',today());
    }

    $.fn.setColumnAttributes = function (key, value, novalue){
        if(novalue===undefined)
            $(this).attr(key,value);
        else
            $(this).removeAttr(key);
    }

    $.fn.formData = function (data, ajaxDone){
        formdata=$(this).serializeArray()
        console.log('serializing form...');
        $(formdata).each(function(i,field){
            data[field.name] = field.value;
        });
        action=$(this).attr('action');
        if(action === undefined){
            console.log('missing form attribute action');
            return;
        }
        $(data['responsediv']).loadData(data, action, ajaxDone);
    }

    $.fn.loadData = function (data, ajaxurl, ajaxDone){
        spinner='<div><div class="spinner-border"></div><div>'+data['loadtxt']+'</div></div>';
        console.log('loading '+data['action']+' data...');
        $(this).html(spinner);
        rdiv=$(this);
        $.post(ajaxurl, data).fail(function(jqXHR, status, error){
            console.log(status+":"+error);
            console.log(jqXHR);
            $(rdiv).errorMessage(status+":"+error);
        }).done(function(html, status){
            $(rdiv).html(html);
            if(data['action']=='initial')loadAdditionalScripts();
            if(ajaxDone!==undefined)ajaxDone();
            if(data['order']!==undefined)sortorder(data);
        });
    }

    $.fn.errorMessage = function (message){
        $(this).html('<div class="alert alert-danger">'+message+'</div>');
    }

    $.fn.debounceKeyup = function (kfunction){
        $(this).keyup(debounce(kfunction,300));
    }
}($));

function loadAdditionalScripts(){
    appendHead('base/syncscroll.js',syncscroll);
    appendBody("assets/dist/js/bootstrap.min.js");
}

function syncscroll(){
    syncscroll.reset();
}

function today(){
    var now = new Date();
    const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: '2-digit', day: '2-digit' });
    const [{ value: month },,{ value: day },,{ value: year }] = dateTimeFormat .formatToParts(today );
    var today=`${year }-${month}-${day}`;
    return today;
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
