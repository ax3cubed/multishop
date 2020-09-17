function enablethemebuttons()
{
    $('.themes-container .data').each(function( key, value ){
        var data = $(this);
        var tid = $(this).data('tid');
        var theme = $(this).data('theme');
        var currentStyle = $(this).data('currentStyle');
        var style = tid+'_style';
        /* enable style selection */
        $(this).find('.theme-selection input:radio[name='+style+']').click(function(){
            var style = $(this).val();
            $('.style-image.'+tid).hide();
            $('.style-image.'+tid+'.'+style).show();
            /* scan current theme button if the selected style is the current style */
            if (currentStyle==style){
                data.find('.button-element input.current').show();
                data.find('.button-element input.publish.alternate').hide();
            }
            else {
                data.find('.button-element input.current').hide();
                data.find('.button-element input.publish.alternate').show();
            }
            
        });
        /* enable update button*/
        var chooseUrl = $(this).data('chooseUrl');
        $(this).find('.ui-button.update').click(function(){
            var selected = $("input:radio[name="+style+"]:checked").val();
            window.location.href =chooseUrl+"?theme="+theme+"&style="+selected;
        });
        /* enable preview button*/
        var previewUrl = $(this).data('previewUrl');
        $(this).find('.ui-button.preview').click(function(){
            var selected = $("input:radio[name="+style+"]:checked").val();
            window.open(previewUrl.replace("{style}", selected),"_blank");
        });
    });
}

function calibratemap(defaultcss,mobilecss){
    if (mobiledisplay())
        $('.shop-map').css(mobilecss);
    else
        $('.shop-map').css(defaultcss);
}
function refreshchosen(){
    $('.chzn-select').chosen();
}
/**
 * This is called when menu item is moved into main menu containter
 */
function sortablereceivemainmenu(ui,limit,limitMessage){
    
    if (ui.item.find('ul.submenu').length == 0 && ui.item.find('div.category').length == 0){//not including category menu
        var submenu = ui.item.attr('id')+'_submenu';
        ui.item.append('<ul class="connectedSortable submenu ui-sortable" id="'+submenu+'"></ul>');//add submenu container
        $('#'+submenu).sortable({
            'delay':'300',
            'connectWith':'.connectedSortable',
            'receive':function(event, ui){sortablereceivesubmenu(ui);},
            'update':function(event, ui){updateheadermenusettings($('#main_menu_container'));},
            'remove':function(event, ui){updateheadermenusettings($('#main_menu_container'));},
        });
    }
    
    var totalMenuItemCount = ui.item.parent().parent().find('li').size();
    var submenuItemCount = ui.item.parent().find('ul li').size();
    var mainmenuItemCount = totalMenuItemCount - submenuItemCount;
    if (mainmenuItemCount > limit) {
        /*ui.sender: will cancel the change. Useful in the sortable 'receive' callback.*/
        $(ui.sender).sortable('cancel');
        alert(limitMessage);
    }
}
/**
 * This is called when menu item is moved into sub menu containter
 */
function sortablereceivesubmenu(ui){
    var menuType = ui.item.find('div.sort-item').data('type');
    
    if (menuType!='category' && ui.item.find('ul.submenu').length > 0){
        ui.item.find('ul.submenu').remove();//remove submenu container (not allowed nested menu, except category submenu)
    }
    if (menuType=='category' && ui.item.find('ul.submenu').length > 0){
        ui.item.find('ul.submenu').addClass('subsubmenu');//add subsubmenu class to indicate 3rd level menu
        ui.item.find('ul.submenu').removeClass('submenu');//remove submenu class
    }
    ui.item.closest('ul').addClass('submenu');
    
}
/**
 * This is called when menu item is discarded and moved back to panel
 */
function sortablereturn(event,ui,message,mandatory_page,mandatory_check){
    if (mandatory_check)
        checkmandatorymenuitem(ui,message,mandatory_page);
    
    //check return menu item must be back to correct place
    var menuType = ui.item.find('div.sort-item').data('type');
    console.log(menuType,event.target);
    if (!$('#'+event.target.id).hasClass(menuType)){
        alert('Invalid containter for menu item of "'+menuType+'" type.');
        $(ui.sender).sortable('cancel');
        return false;
    }
    if (ui.item.find('ul.submenu').length > 0){
        $('#'+ui.item.attr('id')+'_submenu').remove();//remove submenu container when return back to panel
    }
}
/**
 * This is called when menu item is discarded and moved back to panel
 */
function checkmandatorymenuitem(ui,message,mandatory_page){
     if (ui.item.attr('id')==mandatory_page){
        $(ui.sender).sortable('cancel');
        alert(message);
        return false;
    }
}
function updateheadermenusettings(container){
    //console.log('Refresh menu..',container.attr('id'));
    var menu = [];
    container.find('ul.main-menu > li').each(function(i){
        var type = $(this).find('div.sort-item').data('type');
        var url = (type=='link') ? $(this).find('.row.link input').val() : '';
        var heading = type =='link' ? findnavlinkdata($(this)) : '';
        var mainmenu = {
            id : $(this).attr('id'),
            type : type,
            heading : heading,
            url : url,
            items : []
        };
        //console.log('mainmenu',JSON.stringify(mainmenu));
        mainmenu = findsubmenudata($(this),type,mainmenu);
        menu.push(mainmenu);
    });
    console.log('FULL MENU',menu);
    $('#NavigationSettingsForm_mainMenu').val(JSON.stringify(menu));
}
function findsubmenudata(obj,type,data)
{
    obj.find('ul.submenu > li').each(function(j){
        var subtype = type=='category' ? 'subcategory' : $(this).find('div.sort-item').data('type');
        var suburl = (subtype=='link') ? $(this).find('.row.link input').val(): '';
        var subheading = subtype =='link' ? findnavlinkdata($(this)) : '';
        var submenu = {
            id:$(this).attr('id'),
            type:subtype,
            heading:subheading,
            url:suburl
        };
        if (subtype=='category' && $(this).find('ul').length > 0){
            //allow third level when category is a submenu item and contains subcategories
            submenu.items = [];
            submenu = findsubsubmenudata($(this).find('li'),subtype,submenu);
        }
        //console.log('submenu',JSON.stringify(submenu));
        data.items[j] = submenu;
    });
    return data;
}
/**
 * Third level menu - mainly for category sub menu (category menu is the second level item)
 */
function findsubsubmenudata(obj,type,data)
{
    obj.each(function(j){
        var subsubtype = type=='category' ? 'subcategory' : $(this).find('div.sort-item').data('type');
        var subsuburl = (subsubtype=='link') ? $(this).find('.row.link input').val(): '';
        var subsubheading = subsubtype =='link' ? findnavlinkdata($(this)) : '';
        var subsubmenu = {
            id:$(this).attr('id'),
            type:subsubtype,
            heading:subsubheading,
            url:subsuburl
        };
        //console.log('subsubmenu',JSON.stringify(subsubmenu));
        data.items[j] = subsubmenu;
    });
    return data;
}
function findnavlinkdata(container)
{
    if (container.find('div.sort-item.link:first-of-type').length > 0 ){
        var data = {};
        //use first() to avoid selecting submenu one when link menu is a submenu item 
        container.find('div.sort-item.link').first().find('div.view').each(function(k){
            var locale = $(this).find('.row').data('locale');
            data[locale] = $(this).find('#NavigationLinkForm_title_'+locale).val();
        });
        return data;
    }
    else {
        return '';
    }
}

function addnavlink()
{
    var id = $.now();
    var link = '<li  id="link_'+id+'" class="ui-sortable-handle">';
    link += '<div class="sort-item link" data-type="link"><i class="fa fa-arrows"></i><i class="fa fa-chain"></i>';
    link += $('.nav-link-sample').html();
    link += '</div></li>';
    $('ul.connectedSortable.link').append(link);
    $('#link_'+id+' .page-tab').attr('id','yw'+id);
    $('#yw'+id+' ul li').each(function(i){
        var href = $(this).find('a').attr('href');
        $(this).find('a').attr('href',href+id);
    });
    $('#yw'+id+' div.view').each(function(j){
        var divId = $(this).attr('id');
        $(this).attr('id',divId+id);
    });
    $('#yw'+id).yiitab();//enable tab
}

function submitnavmenuform(form,menucontainer)
{
    updateheadermenusettings($('#'+menucontainer));
    submitform(form);
}

function uninstallfbshop(form,action)
{
    $('#'+form).attr("action",action);
    submitform(form);
}

function addnavsubmenu(obj)
{
    alert(obj.parent().html());
}