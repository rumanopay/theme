// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
* Version details
*
* @package    theme_legend
* @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

/**
* @module theme_legend/fix_questionnaire_submit_with_errors
*/
define(['jquery'], function($) {
  var module = {};
  module.fixQuestionnaireSubmitWithErrors = function(){
		function addanswertoarray(array, parent, inline, answer, type) {
			try {
				if (inline) { 
					if (type == 'date') {
						parent.next('.qn-content').children('.qn-answer').prepend('<div class="notifyproblem" role="status"></div>');
						parent.next('.qn-content').children('.qn-answer').find('.notifyproblem').html('The date entered: <strong>'+ answer+'</strong> does not correspond to the format shown in the example.');
						array.push('#'+ parent.find('h2.qn-number').html());
					} else if (type == 'numeric') {
						parent.next('.qn-content').children('.qn-answer').prepend('<div class="notifyproblem" role="status"></div>');
						parent.next('.qn-content').children('.qn-answer').find('.notifyproblem').html('<strong>'+ answer+'</strong> is not an accepted number format.');
						array.push('#'+ parent.find('h2.qn-number').html());
					}
				} else {
					array.push('#'+ parent.find('h2.qn-number').html());
				}
			} catch (error) {

			}
		}

		try {
			$('#phpesp_response').submit(function(e) {  
				$('.notifyproblem').remove();
				
				var unanswered = [];
				var unansweredinline = [];
				var counter = 0; 
				var container;
			  $('#phpesp_response').find('.qn-container').each(function(){
			    container = $(this);
			    var c = container.children('.qn-legend');
			    if(c.has('img.req') && c.find('img.req').length > 0) { 
			    	c.next('.qn-content').find('.qn-answer').each(function() { 
			    		var answers = $(this);
						if (answers.has(':input, select') && answers.find(':input').length > 0) {
			    			answers.find(':input, select').each(function(){
			    				var input = $(this);
			    				var required = false;
			    				switch (input[0].type) {
			                        case 'text': case 'select-one': case 'select-multiple':
			                        	inputval = $(this).val();
			                        	if ($(this).val() == '') {
					    					addanswertoarray(unanswered, c);
						    				counter++;
						    			} 
					    				else if ($(this).parent().hasClass('qn-date')) {
			                        		if (inputval == '') {
					    						addanswertoarray(unanswered, c);
						    					counter++;
						    				} else if (!(/^([1-9]|[12][0-9]|3[01])(\/)([1-9]|1[012])(\/)(\d{4})$/.test(inputval))) {
						    					addanswertoarray(unansweredinline, c, true, inputval, 'date');
						    					counter++;
						    				} 
			                        	} 
						    			else if ($(this).attr('id').toLowerCase().indexOf('numeric') != -1) {
						    				if (!(/^\d+$/.test(inputval))) {
						    					addanswertoarray(unansweredinline, c, true, inputval, 'numeric' );
						    					counter++;
						    				} 
			                        	}
			    					break;
			    					case 'radio':
			    					case 'checkbox':
					    				if (!answers.find('input[type="radio"],input[type="checkbox"]').is(':checked')) { 
						    				addanswertoarray(unanswered, c);
						    				counter++;
						    			} 
			    					break;
			    					default: 
			    					break;
			    				}
			    			});
			    		}
			    	}); 
			    }
			  });    
			  if (counter > 0) { 
			  	e.preventDefault(); 
			  	var adderrorhere = $('.mod_questionnaire_completepage').find('h3:first');
			  	console.log(adderrorhere);
			  	adderrorhere.after('<div class="notifyproblem" role="status"></div>'); 

			  	if (unansweredinline.length > 0 && unanswered.length > 0 ) {
			  		var qtextinline = '';
			  		var qtext = '';
			  		if (unanswered.length > 1) {
			  			qtext = 'Please answer Required questions: ';
			  		} else {
			  			qtext = 'Please answer Required question: ';
			  		}
			  		if (unansweredinline.length > 1) {
			  			qtextinline = '<br> There is something wrong with your answers to questions: ';
			  		} else {
			  			qtextinline = '<br>There is something wrong with your answer to question: ';
			  		}
			  		adderrorhere.next('.notifyproblem').html(qtext + $.unique(unanswered).join('. ') + qtextinline + $.unique(unansweredinline).join('. ') + '.'); 
			  		adderrorhere.next('.notifyproblem')[0].scrollIntoView(false);
			  	} else {
			 		if (unansweredinline.length > 0) {
				  		var qtext = '';
				  		if (unansweredinline.length > 1) {
				  			qtext = 'There is something wrong with your answers to questions: ';
				  		} else {
				  			qtext = 'There is something wrong with your answer to question: ';
				  		}
			  			adderrorhere.next('.notifyproblem').html(qtext + $.unique(unansweredinline).join('. ') + '.'); 
			  			adderrorhere.next('.notifyproblem')[0].scrollIntoView(false);
			  		} 
			  		else if (unanswered.length > 0) {
				  		var qtext = '';
				  		if (unanswered.length > 1 ) {
				  			qtext = 'Please answer Required questions: ';
				  		} else {
				  			qtext = 'Please answer Required question: ';
				  		}
			  			adderrorhere.next('.notifyproblem').html(qtext + $.unique(unanswered).join('. ') + '.');
			  			adderrorhere.next('.notifyproblem')[0].scrollIntoView(false);
			  		}
			  	}
			  }
			});
		} catch (error) {

		}
  };
  return module;
});
