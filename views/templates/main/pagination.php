<? if (!empty($this->item_pages) && is_array($this->item_pages) && count($this->item_pages) > 1): ?>
    <div class="load">
        <span>Показать еще</span>
    </div>

    <div class="pagination">
        <? if ($this->current_page > 1): ?>
            <? if ($this->current_page > 3): ?>
                <a href="?page=1" class="start"></a>
            <? endif; ?>

            <a href="?page=<?=($this->current_page - 1)?>" class="prev"></a>
        <? endif; ?>

        <? if (!empty($this->item_pages) && is_array($this->item_pages)): ?>
            <? foreach ($this->item_pages as $item_page): ?>
                <? if ($item_page < $this->current_page - 2 || $item_page > $this->current_page + 2) continue; ?>

                <? if ($item_page === $this->current_page): ?>
                    <span><?=$item_page?></span>
                <? else: ?>
                    <a href="?page=<?=$item_page?>"><?=$item_page?></a>
                <? endif; ?>
            <? endforeach; ?>
        <? endif; ?>

        <? if ($this->current_page < $this->item_pages[count($this->item_pages)]): ?>
            <a href="?page=<?=($this->current_page + 1)?>" class="next"></a>

            <? if ($this->current_page < $this->item_pages[count($this->item_pages)] - 2): ?>
                <a href="?page=<?=($this->item_pages[count($this->item_pages)])?>" class="end"></a>
            <? endif; ?>
        <? endif; ?>
    </div>
<? endif; ?>
