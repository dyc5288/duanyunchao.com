<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('请不要直接载入这个页面，谢谢!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">这篇文章受到密码保护.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<div class="clear"></div>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
	<?php $comments = array_reverse($comments) ?>
	<h3 id="comments" style="text-align:center"><?php comments_number('赶紧抢沙发', '1个评论', '%个评论' );?>to &#8220;<?php the_title(); ?>&#8221;</h3>
	<ol class="commentlist">
		<?php wp_list_comments('callback=custom_comment');?>
	</ol>
    <?php
		// 如果用户在后台选择要显示评论分页
		if (get_option('page_comments')) {
			// 获取评论分页的 HTML
			$comment_pages = paginate_comments_links('echo=0');
			// 如果评论分页的 HTML 不为空, 显示导航式分页
			if ($comment_pages) {
	?>
		<div class="comment_navi">
			<span>评论分页:</span> <?php echo $comment_pages; ?>
		</div>
	<?php
			}
		}
	?>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
	<!-- If comments are open, but there are no comments. -->
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>
<div id="respond-wrapper">

<div id="respond">

<h3><?php comment_form_title( '发表评论', '发表评论 %s' ); ?></h3>

<div class="cancel-comment-reply">
	<?php cancel_comment_reply_link(); ?>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p>你必须 <a href="<?php echo wp_login_url( get_permalink() ); ?>">登录</a> 才可以留言.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<fieldset>
<?php if ( is_user_logged_in() ) : ?>

<p>当前登录用户 <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">登出 &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="author">姓名 <?php if ($req) echo "(必填)"; ?></label></p>

<p><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
<label for="email">邮件<?php if ($req) echo "(必填)"; ?></label></p>

<p><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
<label for="url">网址</label>(例如：http://wpued.com)</p>

<?php endif; ?>

<!--<p><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></p>-->

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="提交留言" />
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>
</fieldset>


</form>


<?php endif; // If registration required and not logged in ?>

</div>


</div>
<?php endif; // if you delete this the sky will fall on your head ?>
