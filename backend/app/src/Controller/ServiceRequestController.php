<?php

namespace App\Controller;

use App\Model\ServiceRequest;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;

class ServiceRequestController extends Controller
{
    private static array $allowed_actions = [
        'submit',
        'list',
        'updateStatus',
        'options',
    ];

    public function submit(HTTPRequest $request): HTTPResponse
    {
        if ($request->httpMethod() !== 'POST') {
            return $this->json(['error' => 'Method not allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], 400);
        }

        $required = [
            'customerName',
            'customerEmail',
            'siteName',
            'category',
            'priority',
            'description'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->json(['error' => "$field is required"], 422);
            }
        }

        $serviceRequest = ServiceRequest::create();
        $serviceRequest->CustomerName = $data['customerName'];
        $serviceRequest->CustomerEmail = $data['customerEmail'];
        $serviceRequest->SiteName = $data['siteName'];
        $serviceRequest->Category = $data['category'];
        $serviceRequest->Priority = $data['priority'];
        $serviceRequest->Description = $data['description'];
        $serviceRequest->Status = 'New';
        $serviceRequest->write();

        return $this->json([
            'message' => 'Service request created successfully',
            'id' => $serviceRequest->ID,
            'status' => $serviceRequest->Status,
        ], 201);
    }

    public function list(HTTPRequest $request): HTTPResponse
    {
        $items = [];

        foreach (ServiceRequest::get()->sort('Created DESC') as $serviceRequest) {
            $items[] = [
                'id' => $serviceRequest->ID,
                'customerName' => $serviceRequest->CustomerName,
                'customerEmail' => $serviceRequest->CustomerEmail,
                'siteName' => $serviceRequest->SiteName,
                'category' => $serviceRequest->Category,
                'priority' => $serviceRequest->Priority,
                'status' => $serviceRequest->Status,
                'description' => $serviceRequest->Description,
                'created' => $serviceRequest->Created,
            ];
        }

        return $this->json($items);
    }
    public function updateStatus(HTTPRequest $request): HTTPResponse
    {
        if ($request->httpMethod() !== 'POST') {
            return $this->json(['error' => 'Method not allowed'], 405);
        }

        $data = json_decode($request->getBody(), true);

        if (!$data || empty($data['id']) || empty($data['status'])) {
            return $this->json(['error' => 'id and status are required'], 422);
        }

        $allowedStatuses = ['New', 'Assigned', 'InProgress', 'Resolved', 'Closed'];

        if (!in_array($data['status'], $allowedStatuses, true)) {
            return $this->json(['error' => 'Invalid status'], 422);
        }

        $serviceRequest = ServiceRequest::get()->byID((int) $data['id']);

        if (!$serviceRequest) {
            return $this->json(['error' => 'Service request not found'], 404);
        }

        $serviceRequest->Status = $data['status'];
        $serviceRequest->write();

        return $this->json([
            'message' => 'Status updated successfully',
            'id' => $serviceRequest->ID,
            'status' => $serviceRequest->Status,
        ]);
    }

    public function options(HTTPRequest $request): HTTPResponse
    {
        return $this->json([]);
    }

    private function json(array $data, int $status = 200): HTTPResponse
    {
        return HTTPResponse::create(
            json_encode($data),
            $status
        )->addHeader('Content-Type', 'application/json')
        ->addHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
        ->addHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->addHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }

    public function handleRequest(HTTPRequest $request): HTTPResponse
    {
        if ($request->httpMethod() === 'OPTIONS') {
            return $this->json([]);
        }

        return parent::handleRequest($request);
    }
}
