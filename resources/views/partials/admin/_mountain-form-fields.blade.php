{{--
    Shared form body for Add Mountain / Edit Mountain modals.

    Use:
        @include('partials.admin._mountain-form-fields', ['isEdit' => false])

    The wrapping <form> with method, action, csrf, and enctype must be defined
    by the parent. Field values are populated by JS for the edit modal.
--}}
<div class="adm-modal-body">
    <div class="tg-form">
        {{-- Image upload + preview --}}
        <div class="tg-form-row">
            <div style="grid-column:1 / -1;">
                <label>Cover image</label>
                <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
                    <div data-role="image-preview" style="width:120px;height:88px;border-radius:10px;border:1px solid var(--line);background:#f3f4f6;background-size:cover;background-position:center;flex-shrink:0;"></div>
                    <div style="flex:1;min-width:220px;">
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-role="image-input" {{ $isEdit ? '' : 'required' }}>
                        <p style="font-size:11px;color:var(--muted);margin-top:6px;line-height:1.45;">JPG, PNG, or WebP. Up to 8MB. {{ $isEdit ? 'Leave empty to keep the current image.' : 'A cover image is required for new mountains.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Identity --}}
        <div class="tg-form-row">
            <div><label>Mountain name</label><input name="name" required maxlength="120" placeholder="Mt. Batulao"></div>
            <div><label>Location</label><input name="location" required maxlength="160" placeholder="Nasugbu, Batangas"></div>
        </div>
        <div class="tg-form-row">
            <div>
                <label>Difficulty</label>
                <select name="difficulty" required>
                    <option value="Easy">Easy</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Hard">Hard</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
            <div>
                <label>Status</label>
                <select name="status" required>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>

        {{-- Trail metrics --}}
        <div class="tg-form-row">
            <div><label>Elevation label</label><input name="elevation_label" required maxlength="32" placeholder="811 MASL"></div>
            <div><label>Elevation (meters)</label><input name="elevation_meters" type="number" min="0" max="9000" required placeholder="811"></div>
        </div>
        <div class="tg-form-row">
            <div><label>Duration label</label><input name="duration_label" required maxlength="32" placeholder="4-5 hours"></div>
            <div><label>Trail type</label><input name="trail_type_label" required maxlength="64" placeholder="Out & back"></div>
        </div>
        <div class="tg-form-row">
            <div><label>Best time to hike</label><input name="best_time_label" required maxlength="64" placeholder="November to May"></div>
            <div><label>Initial rating (0-5)</label><input name="rating" type="number" min="0" max="5" step="0.1" placeholder="5.0"></div>
        </div>

        {{-- Descriptions --}}
        <div>
            <label>Short description (shown on cards)</label>
            <input name="short_description" maxlength="512" placeholder="Rolling ridges with sunrise-friendly views.">
        </div>
        <div>
            <label>Full description</label>
            <textarea name="full_description" rows="4" required maxlength="5000" placeholder="A more detailed paragraph that appears on the trail page."></textarea>
        </div>

        {{-- Coordinates --}}
        <div style="margin-top:6px;">
            <label style="font-size:13px;font-weight:800;color:var(--text);text-transform:none;letter-spacing:0;">Jump-off (start) and Summit (top) coordinates</label>
            <p style="font-size:11px;color:var(--muted);margin:4px 0 8px;line-height:1.45;">Latitude is north-south (-90 to 90), longitude is east-west (-180 to 180). Use Google Maps "right-click → coordinates" to grab the exact values.</p>
        </div>
        <div class="tg-form-row">
            <div><label>Jump-off latitude</label><input name="jumpoff_lat" type="number" step="any" required placeholder="14.0581288"></div>
            <div><label>Jump-off longitude</label><input name="jumpoff_lng" type="number" step="any" required placeholder="120.8313422"></div>
        </div>
        <div class="tg-form-row">
            <div><label>Summit latitude</label><input name="summit_lat" type="number" step="any" required placeholder="14.0405860"></div>
            <div><label>Summit longitude</label><input name="summit_lng" type="number" step="any" required placeholder="120.8027830"></div>
        </div>

        {{-- Jump-off info --}}
        <div class="tg-form-row">
            <div><label>Jump-off name</label><input name="jumpoff_name" required maxlength="160" placeholder="Old Trail Jump-off"></div>
            <div><label>Meeting time</label><input name="jumpoff_meeting_time" required maxlength="32" placeholder="6:00 AM"></div>
        </div>
        <div>
            <label>Jump-off address</label>
            <input name="jumpoff_address" required maxlength="255" placeholder="Brgy. Cuadra, Nasugbu, Batangas">
        </div>
        <div>
            <label>Jump-off notes (optional)</label>
            <textarea name="jumpoff_notes" rows="2" maxlength="1000" placeholder="Parking, registration, what to expect at the trailhead..."></textarea>
        </div>

        {{-- Weather tracking --}}
        <div style="margin-top:6px;">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:13px;font-weight:700;color:var(--text);text-transform:none;letter-spacing:0;">
                <input type="checkbox" name="enable_weather" value="1" data-role="weather-toggle" style="width:18px;height:18px;accent-color:#10b981;cursor:pointer;">
                <span>Enable live weather tracking (Open-Meteo)</span>
            </label>
            <p style="font-size:11px;color:var(--muted);margin:4px 0 8px;line-height:1.45;">Defaults to the jump-off coordinates. Override below if a nearby weather station lat/lng gives better forecasts.</p>
        </div>
        <div class="tg-form-row" data-role="weather-coords" style="display:none;">
            <div><label>Weather lat (optional)</label><input name="open_meteo_lat" type="number" step="any" placeholder="leave blank to reuse jump-off lat"></div>
            <div><label>Weather lng (optional)</label><input name="open_meteo_lng" type="number" step="any" placeholder="leave blank to reuse jump-off lng"></div>
        </div>

        {{-- Gear --}}
        <div>
            <label>Recommended gear (one per line)</label>
            <textarea name="gear_csv" rows="4" maxlength="2000" placeholder="Headlamp&#10;1L water&#10;Trail snacks"></textarea>
            <p style="font-size:11px;color:var(--muted);margin-top:4px;">Each line becomes a checklist item shown to hikers on the trail page.</p>
        </div>

        {{-- Pricing --}}
        <div style="margin-top:6px;">
            <label style="font-size:13px;font-weight:800;color:var(--text);text-transform:none;letter-spacing:0;">Pricing (PHP, optional)</label>
        </div>
        <div class="tg-form-row">
            <div><label>Registration fee / person</label><input name="registration_fee_per_person" type="number" min="0" step="1" placeholder="0"></div>
            <div><label>Environmental fee / person</label><input name="environmental_fee_per_person" type="number" min="0" step="1" placeholder="0"></div>
        </div>
        <div class="tg-form-row">
            <div><label>Local fee / person</label><input name="local_fee_per_person" type="number" min="0" step="1" placeholder="0"></div>
            <div><label>Guide fee / person</label><input name="guide_fee_per_person" type="number" min="0" step="1" placeholder="0"></div>
        </div>
        <div class="tg-form-row">
            <div><label>Guide fee / group (flat)</label><input name="guide_fee_per_group" type="number" min="0" step="1" placeholder="0"></div>
            <div><label>Emergency contact</label><input name="emergency_contact" maxlength="64" placeholder="Brgy hotline / 911"></div>
        </div>
    </div>
</div>
