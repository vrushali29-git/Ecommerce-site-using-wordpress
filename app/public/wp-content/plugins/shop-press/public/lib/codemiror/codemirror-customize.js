jQuery( window ).on(
	'elementor:init',
	function () {
		var ControlCustomCodeItemView = elementor.modules.controls.BaseData.extend(
			{
				onShow: function () {
					var self = this,
					trigger  = self.ui.textarea[0],
					value    = trigger['value'],
					editor   = CodeMirror.fromTextArea(
						trigger,
						{
							lineNumbers: true,
							showCursorWhenSelecting: true,
							lineWrapping: true,
							matchBrackets: true,
							indentUnit: 4,
							indentWithTabs: true,
							autoCloseTags: true,
							autoCloseBrackets: true,
							mode: 'text/css',
						}
					);
					editor.on(
						'changes',
						function () {
							value = editor.getValue();
							self.setValue( value );
						}
					);
					editor.setValue( value );
				},
			}
		);
		elementor.addControlView(
			'shoppress_custom_code',
			ControlCustomCodeItemView
		);
	}
);
