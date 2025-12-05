<?php 

const CHEQUE_ESPECIAL = 500;
$clientes = [];

function cadastrarCliente(&$clientes): bool {

    $nome = readline('Informe seu nome: ');
    $cpf  = readline('Informe seu CPF: ');

    //validar cliente
    if (isset($clientes[$cpf])) {
        print('Esse CPF já possui cadastro.\n');
        return false;
    }

    $clientes[$cpf] = [
        'nome' => $nome, 
        'cpf' => $cpf,
        'contas' => []
    ];

    return true;
}

function cadastrarConta(array &$clientes): bool {

    $cpf = readline("Informe seu CPF:");

    if (!isset($clientes[$cpf])) {
        print "Cliente não possui cadastro \n";
        return false;
    }

    $numConta = rand(10000, 100000);

    $clientes[$cpf]['contas'][$numConta] = [
        'saldo' => 0,
        'cheque_especial' => CHEQUE_ESPECIAL,
        'extrato' => []
    ];
    

    print "Conta criada com sucesso\n";
    print ("O número da sua conta é: $numConta\n");
    return true;

}

function depositar(array &$clientes){
    $cpf = readline("Informe seu CPF novamente: ");
        if(!isset($clientes[$cpf])) {
        print "Cliente não possui cadastro \n";
        return false;
    }

    $numConta = readline("Informe o número da conta:");
    if (!isset($clientes[$cpf]['contas'][$numConta])) {
        print "Conta não encontrada \n";
        return false;
    }

    $valorDeposito = (float) readline("Informe o valor do depósito: ");

    if ($valorDeposito <= 0) {
        print "Valor de depósito inválido\n";
        return false;
    }


    $clientes[$cpf]['contas'][$numConta]['saldo'] += $valorDeposito;

    $dataHora = date('d/m/Y H:i');
    $clientes[$cpf]['contas'][$numConta]['extrato'][] = "Depósito de R$ $valorDeposito em $dataHora";


    print "Depósito realizado com sucesso\n";
    return true;
}

// MENU PRINCIPAL
function menu(){
    print "\n =============== banco Kame ===============\n";
    print "1 - cadastrar cliente\n";
    print "2 - cadastrar conta\n";
    print "3 - depositar\n";
    print "4 - sacar\n";
    print "5 - consultar saldo\n";
    print "6 - consultar extrato\n";
    print "7 - consultar usuário\n";
    print "8 - sair\n";

    print "Escolha uma opção:";
}

function sacar(array &$clientes){

    $cpf = readline("Informe seu CPF: ");
    if(!isset($clientes[$cpf])) {
        print "Cliente não possui cadastro \n";
        return false;
    }
    //validacao cpf
    $conta = readline("Informe o número da conta:");   
    if(!isset($clientes[$cpf]['contas'][$conta])) {
        print "Conta não encontrada \n";
        return false;
    }
    $valorSaque = (float) readline("Informe o valor do saque: ");


    if ($valorSaque <= 0) {
        print "Valor de saque inválido\n";
        return false;
    } else if( $valorSaque > ($clientes[$cpf]['contas'][$conta]['saldo'] + $clientes[$cpf]['contas'][$conta]['cheque_especial']) ) {
        print "Saldo insuficiente para saque\n";
        return false;
    } else {
        $clientes[$cpf]['contas'][$conta]['saldo'] -= $valorSaque;

        $dataHora = date('d/m/Y H:i');
        $clientes[$cpf]['contas'][$conta]['extrato'][] = "Saque de R$ $valorSaque em $dataHora";

        print "Saque realizado com sucesso\n";
        return true;
    }

    print_r($clientes[$cpf]);
}

function consultarSaldo(array $clientes){
    $cpf = readline("Informe seu CPF: ");
    if(!isset($clientes[$cpf])) {
        print "Cliente não possui cadastro \n";
        return false;
    }
    $conta = readline("Informe o número da conta:");   
    if(!isset($clientes[$cpf]['contas'][$conta])) {
        print "Conta não encontrada \n";
        return false;
    }


    $saldo = $clientes[$cpf]['contas'][$conta]['saldo'];
    $chequeEspecial = CHEQUE_ESPECIAL;

    print "Seu saldo é R$ $saldo\n";
    print "Seu limite de cheque especial é R$ $chequeEspecial\n";
};

function consultarExtrato(array $clientes){
    $cpf = readline("Informe seu CPF: ");
    if(!isset($clientes[$cpf])) {
        print "Cliente não possui cadastro \n";
        return false;
    }
    $conta = readline("Informe o número da conta:");   
    if(!isset($clientes[$cpf]['contas'][$conta])) {
        print "Conta não encontrada \n";
        return false;
    }

    $extrato = $clientes[$cpf]['contas'][$conta]['extrato'];

    print "Seu extrato é:\n";
    foreach ($extrato as $operacao) {
        print "$operacao\n";
    }
};

function consultarUsuario(array $clientes){
    $cpf = readline("Informe seu CPF: ");

    if (!isset($clientes[$cpf])) {
        print "Cliente não possui cadastro \n";
        return false;
    }

    $cliente = $clientes[$cpf];
    print "Nome: " . $cliente['nome'] . "\n";
    print "CPF: " . $cliente['cpf'] . "\n";
    print "Contas:\n";
    foreach ($cliente['contas'] as $numConta => $conta) {
        print "  Conta Número: $numConta\n";
        print "    Saldo: R$ " . $conta['saldo'] . "\n";
        print "    Cheque Especial: R$ " . $conta['cheque_especial'] . "\n";
    }

    return true;
};



//PROGRAMA PRINCIPAL
while(true){

    menu();

    $opcao = readline();

    switch ($opcao) {
        case '1':
            cadastrarCliente($clientes);
            break;
        case '2':
            cadastrarConta($clientes);
            break;
        case '3':
            depositar($clientes);
            break;
        case '4':
            sacar($clientes);
            break;
        case '5':
            consultarSaldo($clientes);
            break;
        case '6':
            consultarExtrato($clientes);
            break;
        case '7':
            consultarUsuario($clientes);
            break;
        
        case '8':
            print "Obrigado por usar nosso banco\n";
            sleep(1);
            system("clear");
            die();
        
        default:
            print "Opção inválida\n";
            break;
    }
}
