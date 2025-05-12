

/***
//--------------------------------------------------------------------
** A-Boss_4/Application/System/js_variables_globales_std.js
//--------------------------------------------------------------------
** Php System  js
//--------------------------------------------------------------------
** B-Objectif
//--------------------------------------------------------------------
**
**	Fichier a inclure dans la page standard 
** *voir code_html.php  write_metaDonnee_html($titleAppli)
**
//--------------------------------------------------------------------
** C-Contenu
//--------------------------------------------------------------------
** liste des variarible globale standad
//-----------------------------------------------------
** version 221005-1-BG
** CRA:
***/
var valMinTinyInt = -128;
var valMaxTinyInt = 127;
var valMinInt = -2147483648;
var valMaxInt = 2147483647;
var valMinFloat = 'NULL';
var valMaxFloat = 'NULL';
var valMinDouble = 'NULL';
var valMaxDouble = 'NULL';
var dateMin = '0000-01-01';
var dateMax = '9999-12-31';

var refCol = [];
var nbSel = 0;
var ligneSel;
var idLigneSel;
var toAdd = true;
var toSupp = false;
var isFirstSel = true;
var indexRowSel;
var rowDataTableSel;
var selectionLignes = '';
var ligneAffichage = '';
var selectionAffichage = '';
var t_selection = [];

var par = [];
var sel = [];
var index_lgn_sel = 0;
var selCol = 0;
var param_json = '';

var isFrameGedShow = false;
var isFrameAgendaShow = false;