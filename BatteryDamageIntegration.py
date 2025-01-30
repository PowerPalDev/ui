import numpy as np
import scipy.integrate as spi

def battery_damage(soc_damage, soc_start, soc_end):
    """
    Calculate the integrated damage caused by charging a battery between two SOC levels.

    Parameters:
    - soc_damage: dict {SOC: damage_contribution} (e.g., {30: 1, 50: 4.5, 70: 4.99, 90: 30})
    - soc_start: Start SOC (%)
    - soc_end: End SOC (%)

    Returns:
    - Integrated damage over the given SOC range
    """

    # Convert dictionary into arrays for interpolation
    soc_levels = np.array(list(soc_damage.keys()))
    damage_levels = np.array(list(soc_damage.values()))

    # Create an interpolation function for damage
    damage_interp = np.interp

    # Define the function to integrate (damage per unit energy stored)
    def damage_function(soc):
        return damage_interp(soc, soc_levels, damage_levels) / 100  # Normalize damage to fraction

    # Perform integration over the SOC range
    total_damage, _ = spi.quad(damage_function, soc_start, soc_end)

    return total_damage

# Example usage
soc_damage_table = {
    30: 1,   # 1% degradation at 30% SOC
    50: 4.5, # 4.5% at 50%
    70: 4.99, # 4.99% at 70%
    90: 30   # 30% at 90%
}

damage_40_to_70 = battery_damage(soc_damage_table, 40, 70)
damage_50_to_90 = battery_damage(soc_damage_table, 50, 90)
damage_0_to_100 = battery_damage(soc_damage_table, 0, 100)

print(f"Damage from 40% to 70%: {damage_40_to_70:.4f}")
print(f"Damage from 50% to 90%: {damage_50_to_90:.4f}")
print(f"Damage from 0% to 100%: {damage_0_to_100:.4f}")
#
#Damage from 40% to 70%: 1.3115
#Damage from 50% to 90%: 4.4480
#Damage from 0% to 100%: 8.2980

