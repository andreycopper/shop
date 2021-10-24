<? if (!empty($this->item_pages) && is_array($this->item_pages) && count($this->item_pages) > 1): ?>
    <div class="load">
        <span>Показать еще</span>
    </div>

    <div class="pagination">
        <? if ($this->pageCurrent > 1): ?>
            <? if ($this->pageCurrent > 3): ?>
                <a href="?page=1" class="start"></a>
            <? endif; ?>

            <a href="?page=<?=($this->pageCurrent - 1)?>" class="prev"></a>
        <? endif; ?>

        <? if (!empty($this->item_pages) && is_array($this->item_pages)): ?>
            <? foreach ($this->item_pages as $item_page): ?>
                <? if ($item_page < $this->pageCurrent - 2 || $item_page > $this->pageCurrent + 2) continue; ?>

                <? if ($item_page === $this->pageCurrent): ?>
                    <span><?=$item_page?></span>
                <? else: ?>
                    <a href="?page=<?=$item_page?>"><?=$item_page?></a>
                <? endif; ?>
            <? endforeach; ?>
        <? endif; ?>

        <? if ($this->pageCurrent < $this->item_pages[count($this->item_pages)]): ?>
            <a href="?page=<?=($this->pageCurrent + 1)?>" class="next"></a>

            <? if ($this->pageCurrent < $this->item_pages[count($this->item_pages)] - 2): ?>
                <a href="?page=<?=($this->item_pages[count($this->item_pages)])?>" class="end"></a>
            <? endif; ?>
        <? endif; ?>
    </div>
<? endif; ?>
