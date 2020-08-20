/*available functions from base/data.js:
 *loadData(data, ajax url, response div id)
 *data array: loadtxt: load text for the loading anumation
              action: for post['action']
              table: table html data
              legalstart: allowed minimum value
              responsediv: the divid where the response should update
 *formData(form id, {'post name':'data'}, ajaxDone)
                                          ^ function to run once the ajax request completes
 *setColumnAttributes(column class, attribute, value, novalue)//novalue can set to 1 to remove the attribute previously set
 *today();
 *dateRangeBoundary(start date id,finish date id)minimum defined in html, maximum set to today()
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
 *debounce(function to debounce, how long the function has to wait before it can refire, disable debounce)
 *$('#id/.class').debounceKeyup(kfunction) shorthand for $('#id/.class').keyup(debounce(kfunction,300)); which will execute kfunction on keyup
 *##################################chainable JQuery functions
 *$('#id/.class').splitAttr(attribute, i) this will split the output of $(this).attr and give the ith element of the attribute
 */

$(document).ready(function(){

    loadData({'action':'initial', 'loadtxt':'Loading', 'responsediv':'#data'}, 'ajax.php');

    function afterAjax(){
        showfilter();
        table=getTable('.table-responsive', 1);
        if(table!==undefined){
            $('.alnk').ajaxInput('href', ajaxLink);
            $('.disabled').reEnableInputs();
        }
    }

    function showfilter(){
        table=$('.table-responsive').html();
        if(table!==undefined)
            $('#filter').removeClass('hidden');
        else
            $('#filter').addClass('hidden');
    }

    function getTable(tableclass, novalue){
        setColumnAttributes('0','width','80',novalue);
        setColumnAttributes('3','width','30',novalue);
        table=$(tableclass).html();
        return table;
    }

    function loadPage(clickBtnValue){
        if(clickBtnValue=='print'){
            table=getTable('.table-responsive');
            loadData({'action': clickBtnValue, 'loadtxt': 'Exporting', 'table':table, 'responsediv':'#data'},'viewer.php');
        }else if(clickBtnValue=='reset'){
            loadData({'action': clickBtnValue, 'loadtxt': 'Resetting', 'responsediv':'#data'}, 'ajax.php');
        }else{
            legalStart=$('#start').attr('min');
            formData('#date', {'action': clickBtnValue, 'loadtxt': 'Loading', 'legalstart':legalStart, 'responsediv':'#data'}, afterAjax);
        }
    }

    $('.btn').ajaxInput('value', function(clickBtnValue, deze){
        $('.navbar-toggler').clickVisible();
        if(clickBtnValue=='print'||clickBtnValue=='reset'){
            $(deze).disableInput('disabled');
        }else if(clickBtnValue=='transaction'){
            $('#reset').disableInput('disabled');
            $('#print').disableInput('disabled');
        }
        table=$('.table-responsive').html();
        if(clickBtnValue=='print' && table===undefined){
            $('#data').errorMessage('No Table To Export');
            return;
        }
        if(clickBtnValue=='reset')
            $('#print').disableInput('disabled');
        if(clickBtnValue!='transaction')
            $('#filter').addClass('hidden');
        loadPage(clickBtnValue);
    });

    function ajaxLink(linkHref, deze){
        var linkID = $(deze).attr('id');
        if(linkID!==undefined){
            formData('#date', {'action': 'transaction', 'loadtxt': 'Sorting', 'order':linkHref, 'linkId':linkID, 'responsediv':'#data'}, afterAjax);
        }else{
            $('#print').disableInput('disabled');
            $('#reset').disableInput('disabled');
            formData('#date', {'action': linkHref, 'loadtxt': 'Loading', 'responsediv':'#data'}, afterAjax);
        }
    }


    $('.lnk').ajaxInput('href', ajaxLink);

    $('#filter').debounceKeyup(function(){
        formData('#date', {'action':'filter','criteria':$(this).val(), 'loadtxt':'Filtering', 'responsediv':'#data'});
    });
});
