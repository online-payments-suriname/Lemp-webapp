/*available functions from data.js:
 *debounce(function to debounce, how long the function has to wait before it can refire, disable debounce)
 *$('#id').loadData(data, ajax url, ajaxDone)
 *data array: loadtxt: load text for the loading anumation
              action: for post['action']
              table: table html data
              legalstart: allowed minimum value
              responsediv: the divid where the response should update
 *ajaxDone: function to run once the ajax request completes
 *$('#formid').formData(data, ajaxDone)send form data to loaddata
 *$('.class').setColumnAttributes(attribute, value, novalue)//novalue can set to 1 to remove the attribute previously set
 *today();
 *$('.class').dateRangeBoundary(start date id,finish date id)minimum defined in html, maximum set to today()
 *$('3id/.class').errorMessage(message)
 *$('#id/.class').disableInput(disabled-class)
 *$('.class').reEnableInputs()
 *$('#id/.class').ajaxInput(targetpage, function to load page)
 *$('#id/.class').clickVisible() only clicks on the element if the element is visible
 *$('.class').iObserve(ofunction, option) function to ulilize intersection observers, it will execute ofunction when firing and it will pass the entry from the intersectionobserver into the function
 *  options is an array containing root, threshold and rootMargin;
 *  root:null is default and is the viewport
 *  threshold is the portion of the element that has to be visible for the observer to fire
 *  rootMargin is a margin the target needs to hit before it fire the observer
 *$('#id/.class').debounceKeyup(kfunction) shorthand for $('#id/.class').keyup(debounce(kfunction,300)); which will execute kfunction on keyup
 *##################################chainable JQuery functions
 *$('#id/.class').splitAttr(attribute, i) this will split the output of $(this).attr and give the ith element of the attribute
 */

$(document).ready(function(){

    $('#block').loadData({'action':'home', 'loadtxt':'Creating form', 'view':'home'}, 'view', afterFormgen);

    function showResphead(entry){
        console.log(entry);
        if(entry.isIntersecting)
            $("#respond").attr('style','display:none');
        else
            $("#respond").removeAttr('style');
    }
    function afterFormgen(){
        $('.btn').ajaxInput('value', ajaxButton);
    }
    function afterAjax(){
        showfilter();
        table=getTable('.table-responsive', 1);
        if(table!==undefined){
            $('.alert').iObserve(showResphead);
            $('.alnk').ajaxInput('href', ajaxLink);
            $('.disabled').reEnableInputs();
        }
    }

    function afterAuth(){
        $('#authorize').submit();
    }

    function showfilter(){
        table=$('.table-responsive').html();
        if(table!==undefined)
            $('#filter').removeClass('hidden');
        else
            $('#filter').addClass('hidden');
    }

    function getTable(tableclass, novalue){
        $('.0').setColumnAttributes('width','80',novalue);
        $('.3').setColumnAttributes('width','30',novalue);
        table=$(tableclass).html();
        return table;
    }

    function crudData(clickBtnValue, table){
        data = {'action': clickBtnValue, 'loadtxt': 'Loading', 'class': table};
        if(clickBtnValue=='destroy'){
            data['loadtxt'] = 'Resetting';
            $('#data').loadData(data, 'ajax');
        }else if(clickBtnValue=='save'){
            data['responsediv'] = '#data';
            $('#form').formData(data, afterAjax);
        }else{
            $('#data').loadData(data, 'ajax', afterAjax);
        }
    }

    function loadPage(clickBtnValue, deze){
        table=$(deze).data('table');
        if(table!=undefined){
            crudData(clickBtnValue, table);
        }else if(clickBtnValue=='print'){
            table=getTable('.table-responsive');
            $('#data').loadData({'action': clickBtnValue, 'loadtxt': 'Exporting', 'table':table, 'view':'pdf-viewer'}, 'view');
        }else if(clickBtnValue=='pay'){
            $('#payment').formData({ 'loadtxt': 'Loading', 'responsediv':'#block'},afterAuth);
        }else{
            $('#form').formData({'action': clickBtnValue, 'loadtxt': 'Loading', 'responsediv':'#data'}, afterAjax);
        }
    }

    function ajaxButton(clickBtnValue, deze){
        if(clickBtnValue=='print'){
            $(deze).disableInput('disabled');
        }
        table=$('.table-responsive').html();
        if(clickBtnValue=='print' && table===undefined){
            $('#data').errorMessage('No Table To Export');
            return;
        }

        if(clickBtnValue=='reset')
            $('#print').disableInput('disabled');
        if(clickBtnValue!='select')
            $('#filter').addClass('hidden');
        loadPage(clickBtnValue, deze);
    }
    $('.btn').ajaxInput('value', ajaxButton);

    function loadLink(linkHref, deze){
        const menu = $(deze).data('page');
        data = { 'loadtxt':'Loading', 'view':linkHref};
        if(menu!=undefined)
            data['model'] = menu;
        $('#block').loadData(data, 'view', afterFormgen);
    }

    function ajaxLink(linkHref, deze){
        var linkID = $(deze).attr('id');
        if(linkID!==undefined){
            $('#form').formData({'action': 'transaction', 'loadtxt': 'Sorting', 'order':linkHref, 'linkId':linkID, 'responsediv':'#data'}, afterAjax);

        }else{
            loadLink(linkHref, deze);
        }
    }


    $('.lnk').ajaxInput('href', ajaxLink);

    $('#filter').debounceKeyup(function(){
        $('#form').formData({'action':'filter','criteria':$(this).val(), 'loadtxt':'Filtering', 'responsediv':'#data'});
    });
});
