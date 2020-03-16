<table class="form-table">
	<tr valign="top">
		<td class="row-title">
            <label for="tablecell">
				Torneio
            </label>
        </td>
		<td>
			<?php echo post_title_meta($post->ID, 'tournament_id'); ?>
		</td>
	</tr>
	<tr valign="top" class="alternate">
		<td class="row-title">
            <label for="tablecell">
                Data da Inscrição
			</label>
		</td>
		<td>
			<?php echo intl_date_meta($post->ID, 'subscribed_at'); ?>
		</td>
	</tr>
	<tr valign="top">
		<td class="row-title">
            <label for="tablecell">
                Categoria
			</label>
		</td>
		<td>
			<?php echo category_meta($post->ID); ?>
		</td>
	</tr>
	<tr valign="top" class="alternate">
		<td class="row-title">
            <label for="tablecell">
                Peso
			</label>
		</td>
		<td>
			<i><?php echo weight_by_author($post->ID, 'weight'); ?></i>
		</td>
	</tr>
</table>