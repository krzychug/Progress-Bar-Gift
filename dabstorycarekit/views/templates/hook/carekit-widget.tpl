{* Zakładamy, że PHP przekazuje:
   carekit_remaining, carekit_threshold, carekit_current_products, carekit_gift_value
*}

<div class="carekit-widget{if $carekit_remaining <= 0} is-qualified{/if}">
  <div class="carekit-header">
     <img class="carekit-icon"
     src="{$module_dir}views/img/gift-fill-svgrepo-com.svg"
     alt=""
     aria-hidden="true"
     height="36">

    <div class="carekit-content">
      <div class="carekit-title">ODBIERZ PREZENT O WARTOŚCI <strong>{$carekit_gift_value|string_format:"%.2f"} zł</strong>
      </div>

      {if $carekit_remaining > 0}
        <div class="carekit-text">
          Brakuje tylko
          <strong>{$carekit_remaining|string_format:"%.2f"} zł</strong>
          do darmowego upominku
        </div>
      {else}
        <div class="carekit-text">
          <strong>Gratulacje!</strong> Próg osiągnięty — prezent o wartości
          <strong>{$carekit_gift_value|string_format:"%.2f"} zł</strong>
          zostanie dodany do zamówienia.
        </div>
      {/if}
    </div>
  </div>

  <div class="carekit-progress-wrapper">
    <div class="carekit-progress-bar">
      <div class="carekit-progress-fill" style="width: {$carekit_percentage}%"></div>
    </div>
    
    <div class="carekit-progress-label">
      {$carekit_current_products|string_format:"%.2f"} zł / {$carekit_threshold|string_format:"%.2f"} zł
    </div>
  </div>

</div>
