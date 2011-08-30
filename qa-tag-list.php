<?php
/*
	Question2Answer Tag List widget plugin, v1.0
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_tag_list
{
	function option_default($option)
	{
		if ( $option == 'tag_list_count_tags' )
			return 30;
	}

	function admin_form()
	{
		$saved = false;

		if ( qa_clicked('tag_list_save_button') )
		{
			qa_opt( 'tag_list_count_tags', (int)qa_post_text('tag_list_count_tags_field') );
			$saved = true;
		}

		return array(
			'ok' => $saved ? 'Tag List settings saved' : null,

			'fields' => array(
				array(
					'label' => 'Number of tags to show:',
					'type' => 'number',
					'value' => (int)qa_opt('tag_list_count_tags'),
					'tags' => 'name="tag_list_count_tags_field"',
				),

			),

			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'name="tag_list_save_button"',
				),
			),
		);
	}

	function allow_template($template)
	{
		switch ($template)
		{
			case 'activity':
			case 'qa':
			case 'questions':
			case 'hot':
			case 'ask':
			case 'categories':
			case 'question':
			case 'tag':
			case 'tags':
			case 'unanswered':
			case 'user':
			case 'users':
			case 'search':
			case 'admin':
				return true;
		}

		return false;
	}

	function allow_region($region)
	{
		return $region == 'side';
	}

	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		require_once QA_INCLUDE_DIR.'qa-db-selects.php';
		$populartags = qa_db_single_select( qa_db_popular_tags_selectspec(0, qa_opt('tag_list_count_tags')) );

		$themeobject->output(
			'<div class="qa-nav-cat-list qa-nav-cat-link">',
			qa_lang_html('main/popular_tags'),
			'</div>'
		);

		$themeobject->output( '<ul class="qa-q-item-tag-list">' );
		foreach ($populartags as $tag => $count)
		{
			$themeobject->output( '<li class="qa-q-item-tag-item">' );
			$themeobject->output( '<a href="' . qa_path_html('tag/'.$tag).'" class="qa-tag-link">' . qa_html($tag) . '</a>' );
			$themeobject->output( ' × ' . $count );
			$themeobject->output( '</li>' );
		}
		$themeobject->output( '</ul>' );
	}

}
