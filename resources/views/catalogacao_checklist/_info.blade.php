<table class="table table-bordered">
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Produto:</td>
    <td class="p-10 d-flex justify-content-between border-0">
      {{ $item->produto->descricao ?? '' }}
      <a href="#" data-id="{{ $item->id }}" data-index="{{ $index }}" class="edit-catalog font-size-10"
        style="text-decoration: none;" title="Editar informações do item">Editar</a>
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Material:</td>
    <td class="p-10" style="width: 85%">{{ $item->material->descricao ?? '' }}
      {{ $item->milesimos ? "$item->milesimos mil" : '' }}</td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Peso:</td>
    <td class="p-10" style="width: 85%">
      <span
        class="{{ $item->edicao && $item->edicao->peso ? 'text-strike' : '' }}">{{ number_format($item->peso, 2, ',', '.') }}
        g</span>
      @if ($item->edicao && $item->edicao->peso)
        <span class="blue-800">{{ number_format($item->edicao->peso, 2, ',', '.') }}</span>
      @endif
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Fornecedor:</td>
    <td class="p-10" style="width: 85%">
      <span
        class="{{ $item->edicao && $item->edicao->idfornec ? 'text-strike' : '' }}">{{ $item->fornecedor->nome ?? '' }}</span>
      @if ($item->edicao && $item->edicao->idfornec)
        <span class="blue-800">{{ $item->edicao->fornecedor->nome ?? '' }}</span>
      @endif
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Referência:</td>
    <td class="p-10" style="width: 85%">
      <span
        class="{{ $item->edicao && $item->edicao->referencia ? 'text-strike' : '' }}">{{ $item->referencia }}</span>
      @if ($item->edicao && $item->edicao->referencia)
        <span class="blue-800">{{ $item->edicao->referencia }}</span>
      @endif
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Bruto:</td>
    <td class="p-10" style="width: 85%">
      @if ($item->desconto)
        <span style="text-decoration:line-through;">
          R$ {{ number_format($item->preco_bruto, 2, ',', '.') }}
        </span>
        <span class="text-danger font-weight-400">R$
          {{ number_format($item->valor_com_desconto, 2, ',', '.') }}</span>
      @else
        R$ {{ number_format($item->preco_bruto, 2, ',', '.') }}
      @endif

    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Banho:</td>
    <td class="p-10" style="width: 85%">R$ {{ number_format($item->custo_total, 2, ',', '.') }}
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Quantidade:</td>
    <td class="p-10" style="width: 85%">
      <span
        class="{{ $item->edicao && $item->edicao->quantidade ? 'text-strike' : '' }}">{{ number_format($item->quantidade, 0, ',', '.') }}</span>
      @if ($item->edicao && $item->edicao->quantidade)
        <span class="blue-800">{{ number_format($item->edicao->quantidade, 0, ',', '.') }}</span>
      @endif
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%">Informação:</td>
    <td class="p-10" style="width: 85%">{{ $item->observacoes }}</td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%; vertical-align: middle;">Obs:</td>
    <td class="p-10" style="width: 85%">
      <div class="form-group form-material mb-0">
        <input type="text" class="form-control" name="itens[{{ $index }}][obs_check]"
          value="{{ $item->obs_check }}" />
      </div>
    </td>
  </tr>
  <tr>
    <td class="p-10 font-weight-500 text-right" style="width: 15%; vertical-align: middle;">Falha:
    </td>
    <td class="p-10" style="width: 85%">
      <div class="form-group form-material mb-0">
        <select class="form-control" name="itens[{{ $index }}][tipo_falha_id]">
          <option value=""></option>
          @foreach ($tiposFalha as $tipoFalha)
            <option value="{{ $tipoFalha->id }}" {{ $item->tipo_falha_id == $tipoFalha->id ? ' selected' : '' }}>
              {{ $tipoFalha->descricao }}</option>
          @endforeach
        </select>
      </div>
    </td>
  </tr>
</table>
