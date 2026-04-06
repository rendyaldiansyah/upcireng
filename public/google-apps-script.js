/**
 * UP Cireng - Google Apps Script (Log / Report only)
 *
 * Laravel = source of truth
 * Google Sheets = log / report mirror
 *
 * 1. Ganti SPREADSHEET_ID dan API_KEY
 * 2. Deploy sebagai Web App
 * 3. Masukkan URL deploy ke GOOGLE_SHEET_WEBHOOK di Laravel
 */

const SPREADSHEET_ID = 'YOUR_SPREADSHEET_ID';
const API_KEY = 'CHANGE_THIS_SECRET_KEY';
const SHEET_ORDERS = 'orders_log';
const SHEET_AUDIT = 'audit_log';

function doPost(e) {
  try {
    const payload = JSON.parse(e.postData.contents || '{}');

    if (payload.api_key !== API_KEY) {
      return jsonResponse({
        success: false,
        message: 'Unauthorized',
      });
    }

    const event = payload.event;
    const order = payload.order || {};
    const meta = payload.meta || {};

    ensureSheets();

    let result;
    switch (event) {
      case 'order.created':
        result = upsertOrderRow(order);
        break;
      case 'order.updated':
        result = upsertOrderRow(order);
        break;
      case 'order.deleted':
        result = markOrderDeleted(order);
        break;
      default:
        result = {
          success: false,
          message: 'Unknown event',
        };
    }

    writeAuditLog(event, order, meta, result);
    return jsonResponse(result);
  } catch (error) {
    return jsonResponse({
      success: false,
      message: error.message || String(error),
    });
  }
}

function ensureSheets() {
  const ss = SpreadsheetApp.openById(SPREADSHEET_ID);

  if (!ss.getSheetByName(SHEET_ORDERS)) {
    const sheet = ss.insertSheet(SHEET_ORDERS);
    sheet.appendRow([
      'Reference',
      'Order ID',
      'Status',
      'Status Label',
      'Customer Name',
      'Customer Email',
      'Customer Phone',
      'Payment Method',
      'Total',
      'Summary',
      'Items JSON',
      'Delivery Address',
      'Notes',
      'Cancel Reason',
      'Payment Proof URL',
      'Sync Event',
      'Ordered At',
      'Completed At',
      'Deleted At',
      'Updated At',
    ]);
  }

  if (!ss.getSheetByName(SHEET_AUDIT)) {
    const audit = ss.insertSheet(SHEET_AUDIT);
    audit.appendRow([
      'Timestamp',
      'Event',
      'Reference',
      'Order ID',
      'Success',
      'Message',
    ]);
  }
}

function upsertOrderRow(order) {
  const sheet = SpreadsheetApp.openById(SPREADSHEET_ID).getSheetByName(SHEET_ORDERS);
  const rowIndex = findOrderRow(sheet, order.reference, order.id);

  const rowValues = [
    order.reference || '',
    order.id || '',
    order.status || '',
    order.status_label || '',
    order.customer ? order.customer.name : '',
    order.customer ? order.customer.email : '',
    order.customer ? order.customer.phone : '',
    order.payment_method || '',
    order.total_price || 0,
    order.summary_title || '',
    JSON.stringify(order.items || []),
    order.delivery_address || '',
    order.notes || '',
    order.cancel_reason || '',
    order.payment_proof_url || '',
    'UPSERT',
    order.timestamps ? order.timestamps.ordered_at : '',
    order.timestamps ? order.timestamps.completed_at : '',
    order.timestamps ? order.timestamps.deleted_at : '',
    new Date(),
  ];

  if (rowIndex > 0) {
    sheet.getRange(rowIndex, 1, 1, rowValues.length).setValues([rowValues]);
  } else {
    sheet.appendRow(rowValues);
  }

  return {
    success: true,
    message: 'Order log updated',
  };
}

function markOrderDeleted(order) {
  const sheet = SpreadsheetApp.openById(SPREADSHEET_ID).getSheetByName(SHEET_ORDERS);
  const rowIndex = findOrderRow(sheet, order.reference, order.id);

  if (rowIndex < 1) {
    return {
      success: false,
      message: 'Order not found in sheet',
    };
  }

  sheet.getRange(rowIndex, 3).setValue('deleted');
  sheet.getRange(rowIndex, 16).setValue('DELETE');
  sheet.getRange(rowIndex, 19).setValue(order.timestamps ? order.timestamps.deleted_at : new Date());
  sheet.getRange(rowIndex, 20).setValue(new Date());

  return {
    success: true,
    message: 'Order marked deleted',
  };
}

function findOrderRow(sheet, reference, orderId) {
  const values = sheet.getDataRange().getValues();

  for (let i = 1; i < values.length; i += 1) {
    if (String(values[i][0]) === String(reference) || String(values[i][1]) === String(orderId)) {
      return i + 1;
    }
  }

  return -1;
}

function writeAuditLog(event, order, meta, result) {
  const audit = SpreadsheetApp.openById(SPREADSHEET_ID).getSheetByName(SHEET_AUDIT);
  audit.appendRow([
    new Date(),
    event,
    order.reference || '',
    order.id || '',
    result.success ? 'YES' : 'NO',
    result.message || '',
  ]);
}

function jsonResponse(data) {
  return ContentService
    .createTextOutput(JSON.stringify(data))
    .setMimeType(ContentService.MimeType.JSON);
}
