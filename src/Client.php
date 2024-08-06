<?php


namespace Rostro\Myaccount;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use RuntimeException;

class Client
{
    const HOST = 'https://myaccount.systemportals.com';

    protected string $host;

    protected object $accessToken;

    protected GuzzleClient $guzzle;

    public function __construct(array $options)
    {
        $this->host = $options['host'] ?? self::HOST;
        $this->guzzle = new GuzzleClient();
        $this->login($options);
    }

    private function login(array $options): void
    {
        $url = $this->getUrl('login');

        $options = [
            'form_params' => [
                'email' => $options['username'],
                'password' => $options['password'],
            ],
        ];

        $response = $this->guzzle->request('POST', $url, $options);
        $response = json_decode($response->getBody());

        if ($response->result == 'error') {
            $error = $response->errors[0] ?? 'Unknown error';
            throw new RuntimeException($error);
        }

        $this->accessToken = $response;
    }

    private function apiRequest(string $api, ?array $parameters = []): array|object
    {
        $headers = $this->headers();
        $url = $this->getUrl($api);
        $request = new GuzzleRequest('GET', $url, $headers);
        $options = ['query' => $parameters];

        $response = $this->guzzle->send($request, $options);

        if ($response->getStatusCode() !== 200) {
            print_r(json_decode($response->getBody()));
            throw new RuntimeException('API request failed');
        }

        return json_decode($response->getBody());
    }

    private function headers(): array
    {
        $accept = ['Accept' => 'application/json'];

        if ($this->accessToken->result == 'success') {
            return ['Authorization' => 'Bearer ' . $this->accessToken->token] + $accept;
        }

        return $accept;
    }

    private function getUrl($path): string
    {
        return $this->host . '/api/' . $path;
    }

    /**
     * Get information about your user account.
     *
     * @return array|object
     */
    public function me()
    {
        return $this->apiRequest('user');
    }

    /**
     * Get Iress commissions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressCommissions($params = [])
    {
        return $this->apiRequest('iress/commissions', $params);
    }

    /**
     * Get Iress dividends data.
     *
     * @param $params
     * @return array|object
     */
    public function iressDividends($params = [])
    {
        return $this->apiRequest('iress/dividends', $params);
    }

    /**
     * Get Iress dividend tax data.
     *
     * @param $params
     * @return array|object
     */
    public function iressDividendTax($params = [])
    {
        return $this->apiRequest('ress/dividendtax', $params);
    }

    /**
     * Get Iress financing data.
     *
     * @param $params
     * @return array|object
     */
    public function iressFinancing($params = [])
    {
        return $this->apiRequest('iress/financing', $params);
    }

    /**
     * Get Iress Greek tax data.
     *
     * @param $params
     * @return array|object
     */
    public function iressPGreekTax($params = [])
    {
        return $this->apiRequest('iress/greektax', $params);
    }

    /**
     * Get Iress interest data.
     *
     * @param $params
     * @return array|object
     */
    public function iressInterest($params = [])
    {
        return $this->apiRequest('iress/interest', $params);
    }

    /**
     * Get Iress Italian tax data.
     *
     * @param $params
     * @return array|object
     */
    public function iressItalianTax($params = [])
    {
        return $this->apiRequest('iress/italiantax', $params);
    }

    /**
     * Get Iress short borrowing data.
     *
     * @param $params
     * @return array|object
     */
    public function iressShortBorrowing($params = [])
    {
        return $this->apiRequest('iress/shortborrowing', $params);
    }

    /**
     * Get Iress money flow data.
     *
     * @param $params
     * @return array|object
     */
    public function iressMoneyFlow($params = [])
    {
        return $this->apiRequest('iress/moneyflow', $params);
    }

    /**
     * Get Iress money flow totals data.
     *
     * @param $params
     * @return array|object
     */
    public function iressMoneyFlowTotals($params = [])
    {
        return $this->apiRequest('iress/moneyflow/totals', $params);
    }

    /**
     * Get Iress money flow net data.
     *
     * @param $params
     * @return array|object
     */
    public function iressMoneyFlowNet($params = [])
    {
        return $this->apiRequest('iress/moneyflow/net', $params);
    }

    /**
     * Get Iress money flow net totals data.
     *
     * @param $params
     * @return array|object
     */
    public function iressMoneyFlowNetTotals($params = [])
    {
        return $this->apiRequest('iress/moneyflow/net/totals', $params);
    }

    /**
     * Get Iress statement average positions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsAvgPositions($params = [])
    {
        return $this->apiRequest('iress/statements/avgpositions', $params);
    }

    /**
     * Get Iress statement commissions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsCommissions($params = [])
    {
        return $this->apiRequest('iress/statements/commissions', $params);
    }

    /**
     * Get Iress statement financing data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsFinancing($params = [])
    {
        return $this->apiRequest('iress/statements/financing', $params);
    }

    /**
     * Get Iress statement positions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsPositions($params = [])
    {
        return $this->apiRequest('iress/statements/positions', $params);
    }

    /**
     * Get Iress statement trades data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsTrades($params = [])
    {
        return $this->apiRequest('iress/statements/trades', $params);
    }

    /**
     * Get Iress statement transactions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsTransactions($params = [])
    {
        return $this->apiRequest('iress/statements/transactions', $params);
    }

    /**
     * Get Iress statement account balances totals data.
     *
     * @param $params
     * @return array|object
     */
    public function iressStatementsTotals($params = [])
    {
        return $this->apiRequest('iress/statements/totals', $params);
    }

    /**
     * Get Iress transactions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressTransactions($params = [])
    {
        return $this->apiRequest('iress/transactions', $params);
    }

    /**
     * Get Iress account balances data.
     *
     * @param $params
     * @return array|object
     */
    public function iressAccountBalances($params = [])
    {
        return $this->apiRequest('iress/accountbalances', $params);
    }

    /**
     * Get Iress average positions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressAvgPositions($params = [])
    {
        return $this->apiRequest('iress/avgpositions', $params);
    }

    /**
     * Get Iress net balances data.
     *
     * @param $params
     * @return array|object
     */
    public function iressNetBalances($params = [])
    {
        return $this->apiRequest('iress/netbalances', $params);
    }

    /**
     * Get Iress positions data.
     *
     * @param $params
     * @return array|object
     */
    public function iressPositions($params = [])
    {
        return $this->apiRequest('iress/positions', $params);
    }

    /**
     * Get Iress trades data.
     *
     * @param $params
     * @return array|object
     */
    public function iressTrades($params = [])
    {
        return $this->apiRequest('iress/trades', $params);
    }

    /**
     * Get Devex commissions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexCommissions($params = [])
    {
        return $this->apiRequest('devex/commissions', $params);
    }

    /**
     * Get Devex financing data.
     *
     * @param $params
     * @return array|object
     */
    public function devexFinancing($params = [])
    {
        return $this->apiRequest('devex/financing', $params);
    }

    /**
     * Get Devex interest data.
     *
     * @param $params
     * @return array|object
     */
    public function devexInterest($params = [])
    {
        return $this->apiRequest('devex/interest', $params);
    }

    /**
     * Get Devex Italian tax data.
     *
     * @param $params
     * @return array|object
     */
    public function devexItalianTax($params = [])
    {
        return $this->apiRequest('devex/italiantax', $params);
    }

    /**
     * Get Devex short borrowing data.
     *
     * @param $params
     * @return array|object
     */
    public function devexShortBorrowing($params = [])
    {
        return $this->apiRequest('devex/shortborrowing', $params);
    }

    /**
     * Get Devex money flow data.
     *
     * @param $params
     * @return array|object
     */
    public function devexMoneyFlow($params = [])
    {
        return $this->apiRequest('devex/moneyflow', $params);
    }

    /**
     * Get Devex money flow totals data.
     *
     * @param $params
     * @return array|object
     */
    public function devexMoneyFlowTotals($params = [])
    {
        return $this->apiRequest('devex/moneyflow/totals', $params);
    }

    /**
     * Get Devex statement average positions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsAvgPositions($params = [])
    {
        return $this->apiRequest('devex/statements/avgpositions', $params);
    }

    /**
     * Get Devex statement commissions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsCommissions($params = [])
    {
        return $this->apiRequest('devex/statements/commissions', $params);
    }

    /**
     * Get Devex statement financing data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsFinancing($params = [])
    {
        return $this->apiRequest('devex/statements/financing', $params);
    }

    /**
     * Get Devex statement positions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsPositions($params = [])
    {
        return $this->apiRequest('devex/statements/positions', $params);
    }

    /**
     * Get Devex statement short borrowing data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsShortBorrowing($params = [])
    {
        return $this->apiRequest('devex/statements/shortborrowing', $params);
    }

    /**
     * Get Devex statement trades data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsTrades($params = [])
    {
        return $this->apiRequest('devex/statements/trades', $params);
    }

    /**
     * Get Devex statement transactions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsTransactions($params = [])
    {
        return $this->apiRequest('devex/statements/transactions', $params);
    }

    /**
     * Get Devex statement account balances totals data.
     *
     * @param $params
     * @return array|object
     */
    public function devexStatementsTotals($params = [])
    {
        return $this->apiRequest('devex/statements/totals', $params);
    }

    /**
     * Get Devex transactions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexTransactions($params = [])
    {
        return $this->apiRequest('devex/transactions', $params);
    }

    /**
     * Get Devex average positions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexAvgPositions($params = [])
    {
        return $this->apiRequest('devex/avgpositions', $params);
    }

    /**
     * Get Devex closed positions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexClosedPositions($params = [])
    {
        return $this->apiRequest('devex/closedpositions', $params);
    }

    /**
     * Get Devex positions data.
     *
     * @param $params
     * @return array|object
     */
    public function devexPositions($params = [])
    {
        return $this->apiRequest('devex/positions', $params);
    }

    /**
     * Get Devex trades data.
     *
     * @param $params
     * @return array|object
     */
    public function devexTrades($params = [])
    {
        return $this->apiRequest('devex/trades', $params);
    }

    /**
     * Get the clients data.
     *
     * @param $params
     * @return array|object
     */
    public function getClients($params = [])
    {
        return $this->apiRequest('clients', $params);
    }

    /**
     * Get the platform users data.
     *
     * @param $params
     * @return array|object
     */
    public function getPlatformUsers($params = [])
    {
        return $this->apiRequest('clients/users', $params);
    }

    /**
     * Get the platform accounts data.
     *
     * @param $params
     * @return array|object
     */
    public function getPlatformAccounts($params = [])
    {
        return $this->apiRequest('clients/accounts', $params);
    }
}
